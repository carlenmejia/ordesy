<?php

namespace Modules\CashRegister\Livewire;

use Livewire\Component;
use Modules\CashRegister\Entities\CashRegister;
use Modules\CashRegister\Entities\CashRegisterSession;
use Modules\CashRegister\Entities\CashRegisterTransaction;
use Modules\CashRegister\Entities\CashRegisterCount;
use Modules\CashRegister\Entities\CashDenomination;
use Modules\CashRegister\Entities\Denomination;
use Modules\CashRegister\Services\RegisterForceOpenService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CashierWidget extends Component
{
    use LivewireAlert;
    public ?CashRegisterSession $session = null;
    public $openingFloat = 0;
    public $cashSales = 0;
    public $cashOut = 0;
    public $safeDrop = 0;
    public $expectedCash = 0;

    public string $reason = '';
    public $amount = 0;
    public bool $showClose = false;
    public $countedCash = 0;
    public array $denoms = [];
    public string $closingNote = '';
    public bool $usingDefaultDenoms = false;
    public bool $forceOpen = false;

    protected array $rules = [
        'amount' => 'required|numeric|min:0.01',
        'reason' => 'nullable|string|min:0|max:255',
    ];

    // Confirmation modal state
    public bool $confirming = false;
    public string $confirmAction = '';
    public string $confirmTitle = '';
    public string $confirmMessage = '';

    public function mount(): void
    {
        $this->loadSession();
    }

    private function loadSession(): void
    {
        $register = CashRegister::firstOrCreate([
            'restaurant_id' => restaurant()->id ?? 0,
            'branch_id' => branch()->id ?? 0,
            'name' => 'Default Register',
        ]);

        // If user can view all reports, show open register from any branch
        // Otherwise, show only from current branch
        $query = CashRegisterSession::query()
            ->where('restaurant_id', restaurant()->id ?? 0)
            ->where('opened_by', user()->id)
            ->where('status', 'open');

        if (user_can('View Cash Register Reports')) {
            // Show open register from any branch
            $this->session = $query->latest('opened_at')->first();
        } else {
            // Show only from current branch
            $this->session = $query->where('branch_id', branch()->id ?? 0)
                ->latest('opened_at')
                ->first();
        }

        // Determine if this user should be forced to open register after login
        $this->forceOpen = RegisterForceOpenService::shouldForceOpenRegister(user());

        if ($this->session) {
            $this->openingFloat = (float) $this->session->opening_float;
            $this->refreshTotals();
        }
    }

    private function refreshTotals(): void
    {
        if (!$this->session) {
            $this->cashSales = 0;
            $this->cashOut = 0;
            $this->safeDrop = 0;
            $this->expectedCash = 0;
            return;
        }

        // Calculate everything fresh from database for accuracy
        $this->cashSales = (float) CashRegisterTransaction::where('cash_register_session_id', $this->session->id)
            ->whereIn('type', ['cash_sale', 'cash_in'])
            ->sum('amount');

        $this->cashOut = (float) CashRegisterTransaction::where('cash_register_session_id', $this->session->id)
            ->where('type', 'cash_out')
            ->sum('amount');

        $this->safeDrop = (float) CashRegisterTransaction::where('cash_register_session_id', $this->session->id)
            ->where('type', 'safe_drop')
            ->sum('amount');

        // Calculate expected cash fresh
        $this->expectedCash = (float) $this->openingFloat + $this->cashSales - $this->cashOut - $this->safeDrop;
    }

    public function getExpectedProperty(): float
    {
        if (!$this->session) {
            return 0.0;
        }

        // Calculate fresh from database every time for accuracy
        $opening = (float) $this->openingFloat;

        $cashSales = (float) CashRegisterTransaction::where('cash_register_session_id', $this->session->id)
            ->whereIn('type', ['cash_sale', 'cash_in'])
            ->sum('amount');

        $cashOut = (float) CashRegisterTransaction::where('cash_register_session_id', $this->session->id)
            ->where('type', 'cash_out')
            ->sum('amount');

        $safeDrop = (float) CashRegisterTransaction::where('cash_register_session_id', $this->session->id)
            ->where('type', 'safe_drop')
            ->sum('amount');

        $expected = $opening + $cashSales - $cashOut - $safeDrop;

        // Update the property for consistency
        $this->expectedCash = $expected;

        return $expected;
    }

    public function openRegister(): mixed
    {

        // Permission check
        if (!user_can('Open Cash Register')) {
            session()->flash('message', __('You do not have permission to open the register.'));
            // return null;
        }

        if ($this->session) {
            return null; // already open
        }

        $register = CashRegister::firstOrCreate([
            'restaurant_id' => restaurant()->id ?? 0,
            'branch_id' => branch()->id ?? 0,
            'name' => 'Default Register',
        ]);

        $this->session = CashRegisterSession::create([
            'cash_register_id' => $register->id,
            'restaurant_id' => restaurant()->id ?? 0,
            'branch_id' => branch()->id ?? 0,
            'opened_by' => user()->id,
            'opened_at' => now(),
            'opening_float' => $this->openingFloat,
            'status' => 'open',
        ]);

        // If there is an intended URL after opening, redirect there
        if (session()->has('intended_after_register')) {
            $url = session()->pull('intended_after_register');
            // Guard: avoid redirecting to manifest or non-HTML assets
            if (is_string($url) && !str_contains($url, 'manifest.json')) {
                return redirect()->to($url);
            }
        }

        // Fallback to cashier page to avoid PWA manifest redirects
        return redirect()->route('cashregister.cashier');
    }

    public function doCashIn(): void
    {
        if (!$this->session) {
            session()->flash('message', 'Open the register first.');
            return;
        }
        $this->validate();
        $amount = (float) $this->amount;
        CashRegisterTransaction::create([
            'cash_register_session_id' => $this->session->id,
            'restaurant_id' => restaurant()->id ?? 0,
            'branch_id' => branch()->id ?? 0,
            'happened_at' => now(),
            'type' => 'cash_in',
            'reason' => $this->reason,
            'amount' => $amount,
            'created_by' => user()->id,
        ]);
        // Refresh the totals to reflect the new cash_in transaction
        $this->refreshTotals();
        $this->reset(['amount', 'reason']);
        session()->flash('message', 'Cash In recorded.');
    }

    public function doCashOut(): void
    {
        if (!$this->session) {
            session()->flash('message', 'Open the register first.');
            return;
        }
        $this->validate();
        $amount = (float) $this->amount;
        // Prevent overdraft beyond expected cash
        if ($amount > (float) $this->expectedCash) {
            $this->addError('amount', 'Amount exceeds expected cash.');
            return;
        }
        CashRegisterTransaction::create([
            'cash_register_session_id' => $this->session->id,
            'restaurant_id' => restaurant()->id ?? 0,
            'branch_id' => branch()->id ?? 0,
            'happened_at' => now(),
            'type' => 'cash_out',
            'reason' => $this->reason,
            'amount' => $amount,
            'created_by' => user()->id,
        ]);
        $this->refreshTotals();
        $this->reset(['amount', 'reason']);
        session()->flash('message', 'Cash Out recorded.');
    }

    public function doSafeDrop(): void
    {
        if (!$this->session) {
            session()->flash('message', 'Open the register first.');
            return;
        }
        $this->validate();
        $amount = (float) $this->amount;
        // Prevent overdraft beyond expected cash
        if ($amount > (float) $this->expectedCash) {
            $this->addError('amount', 'Amount exceeds expected cash.');
            return;
        }
        CashRegisterTransaction::create([
            'cash_register_session_id' => $this->session->id,
            'restaurant_id' => restaurant()->id ?? 0,
            'branch_id' => branch()->id ?? 0,
            'happened_at' => now(),
            'type' => 'safe_drop',
            'reason' => $this->reason,
            'amount' => $amount,
            'created_by' => user()->id,
        ]);
        $this->refreshTotals();
        $this->reset(['amount', 'reason']);
        session()->flash('message', 'Safe Drop recorded.');
    }

    // Confirmation flows
    public function confirmCashIn(): void
    {
        if (!$this->session) {
            session()->flash('message', 'Open the register first.');
            return;
        }
        $this->validate();
        $this->confirmAction = 'cash_in';
        $this->confirmTitle = 'Confirm Cash In';
        $this->confirmMessage = 'Are you sure you want to record this Cash In?';
        $this->confirming = true;
    }

    public function confirmCashOut(): void
    {
        if (!$this->session) {
            session()->flash('message', 'Open the register first.');
            return;
        }
        $this->validate();
        $this->confirmAction = 'cash_out';
        $this->confirmTitle = 'Confirm Cash Out';
        $this->confirmMessage = 'Are you sure you want to record this Cash Out?';
        $this->confirming = true;
    }

    public function confirmSafeDrop(): void
    {
        if (!$this->session) {
            session()->flash('message', 'Open the register first.');
            return;
        }
        $this->validate();
        $this->confirmAction = 'safe_drop';
        $this->confirmTitle = 'Confirm Safe Drop';
        $this->confirmMessage = 'Are you sure you want to record this Safe Drop?';
        $this->confirming = true;
    }

    public function confirmSubmitClosing(): void
    {
        if (!$this->session) {
            session()->flash('message', 'Open the register first.');
            return;
        }
        $this->confirmAction = 'submit_closing';
        $this->confirmTitle = 'Submit Closing for Approval';
        $this->confirmMessage = 'Are you sure you want to submit this closing for approval?';
        $this->confirming = true;
    }

    public function performConfirmed(): void
    {
        $this->confirming = false;
        switch ($this->confirmAction) {
            case 'cash_in':
                $this->doCashIn();
                break;
            case 'cash_out':
                $this->doCashOut();
                break;
            case 'safe_drop':
                $this->doSafeDrop();
                break;
            case 'submit_closing':
                $this->submitClosing();
                break;
        }
        $this->confirmAction = '';
        $this->confirmTitle = '';
        $this->confirmMessage = '';

        // Force refresh of all totals after confirmation
        $this->refreshTotals();
    }

    public function refreshData()
    {
        $this->refreshTotals();
    }

    public function render()
    {
        return view('cashregister::livewire.cashier-widget');
    }

    public function xReport()
    {
        return redirect()->route('cashregister.reports', ['tab' => 'x']);
    }

    public function startClose(): void
    {
        if (!$this->session) return;
        $this->showClose = true;
        $this->usingDefaultDenoms = false;
        // Load active denominations for current restaurant/branch (currency removed)
        $this->denoms = Denomination::query()
            ->where(function ($q) { $q->where('restaurant_id', restaurant()->id ?? null)->orWhereNull('restaurant_id'); })
            ->where(function ($q) { $q->where('branch_id', branch()->id ?? null)->orWhereNull('branch_id'); })
            ->where('is_active', true)
            ->orderByDesc('value')
            ->get(['id', 'value'])
            ->map(fn($d) => ['id' => $d->id, 'value' => $d->value, 'count' => 0, 'subtotal' => 0])
            ->toArray();

        if (empty($this->denoms)) {
            $this->alert('error', 'No denominations configured. Please configure denominations in settings.');
            return;
        }
    }

    public function updatedDenoms(): void
    {
        foreach ($this->denoms as &$d) {
            $d['subtotal'] = ((int)($d['count'] ?? 0)) * ((int)$d['value']);
        }
        unset($d);
        $this->countedCash = array_sum(array_column($this->denoms, 'subtotal'));
    }

    public function updated($name): void
    {
        if (str_starts_with($name, 'denoms.')) {
            $this->updatedDenoms();
        }
    }

    public function submitClosing(): void
    {
        if (!$this->session) return;

        // Check if denominations are configured (currency removed)
        $denominationsExist = Denomination::query()
            ->where(function ($q) { $q->where('restaurant_id', restaurant()->id ?? null)->orWhereNull('restaurant_id'); })
            ->where(function ($q) { $q->where('branch_id', branch()->id ?? null)->orWhereNull('branch_id'); })
            ->where('is_active', true)
            ->exists();

        if (!$denominationsExist) {
            $this->alert('error', 'No denominations configured. Please configure denominations in settings before closing the register.');
            return;
        }

        $expected = $this->expectedCash;
        $this->session->expected_cash = $expected;
        $this->session->counted_cash = $this->countedCash;
        $this->session->discrepancy = $this->countedCash - $expected;
        $this->session->closing_note = $this->closingNote;
        $this->session->status = 'pending_approval';
        $this->session->closed_by = user()->id;
        $this->session->closed_at = now();
        $this->session->save();

        foreach ($this->denoms as $d) {
            $count = (int)($d['count'] ?? 0);
            $subtotal = (int)($d['subtotal'] ?? 0);
            $denominationId = $d['id'] ?? null;

            if ($denominationId && $count > 0) {
                CashRegisterCount::create([
                    'cash_register_session_id' => $this->session->id,
                    'cash_denomination_id' => $denominationId,
                    'count' => $count,
                    'subtotal' => $subtotal,
                ]);

                // Debug: Log saved denomination
                Log::info('[Cashier] Saved denomination: Session ' . $this->session->id . ', Denomination ID ' . $denominationId . ' × ' . $count . ' = ' . $subtotal);
            }
        }

        // Reset UI state after closing
        $this->reset(['amount', 'reason', 'showClose', 'countedCash', 'denoms', 'closingNote']);
        $this->openingFloat = 0;
        $this->cashSales = 0;
        $this->cashOut = 0;
        $this->safeDrop = 0;
        $this->session = null;
        session()->flash('message', 'Closing submitted for approval. Register is now closed.');
    }
}
