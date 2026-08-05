@php
    $selectedType = old('type', $transaction->type ?? 'expense');
    $selectedCategory = old('category_id', $transaction->category_id ?? '');
@endphp
<form class="form-stack" method="POST" action="{{ $action }}" data-draft-form data-draft-key="{{ $draftKey }}">
    @csrf
    @isset($method)
        @method($method)
    @endisset

    <div class="quick-grid">
        <div class="field">
            <label class="field-label" for="type">Type</label>
            <select id="type" class="control" name="type">
                <option value="expense" @selected($selectedType === 'expense')>Expense</option>
                <option value="income" @selected($selectedType === 'income')>Income</option>
            </select>
        </div>
        <div class="field">
            <label class="field-label" for="amount">Amount</label>
            <input id="amount" class="control control--large" name="amount" type="number" min="0" step="0.01" inputmode="decimal" value="{{ old('amount', $transaction->amount ?? '') }}" autofocus data-autofocus-amount>
        </div>
    </div>

    <div class="field">
        <label class="field-label" for="description">Description</label>
        <input id="description" class="control" name="description" type="text" maxlength="255" value="{{ old('description', $transaction->description ?? '') }}">
    </div>

    <div class="quick-grid">
        <div class="field">
            <label class="field-label" for="category_id">Category</label>
            <select id="category_id" class="control" name="category_id">
                <option value="">Select category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) $selectedCategory === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label class="field-label" for="transaction_date">Date</label>
            <input id="transaction_date" class="control" name="transaction_date" type="date" value="{{ old('transaction_date', optional($transaction->transaction_date ?? null)->format('Y-m-d') ?? now()->toDateString()) }}">
        </div>
    </div>

    <div class="quick-grid">
        <div class="field">
            <label class="field-label" for="payment_method">Payment method</label>
            <input id="payment_method" class="control" name="payment_method" type="text" maxlength="50" value="{{ old('payment_method', $transaction->payment_method ?? '') }}">
        </div>
        <div class="field">
            <label class="field-label" for="notes">Notes</label>
            <textarea id="notes" class="control control--textarea" name="notes" maxlength="1000" rows="3">{{ old('notes', $transaction->notes ?? '') }}</textarea>
        </div>
    </div>

    <button class="btn btn-primary btn-block" type="submit">{{ $submitLabel }}</button>
</form>
