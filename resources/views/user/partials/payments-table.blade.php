@forelse($payments as $payment)
<tr>
    <td>
        <a href="{{ $payment->invoice_id ? route('invoice.show', $payment->invoice_id) : '#' }}" 
           class="link-primary fw-medium">
            #{{ $payment->payment_id ?? 'PA-' . str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}
        </a>
        @if($payment->transaction_id)
            <br><small class="text-muted">Txn: {{ $payment->transaction_id }}</small>
        @endif
    </td>
    <td class="text-gray-9 fw-medium">{{ $payment->service_name ?? 'N/A' }}</td>
    <td>{{ $payment->service_type ?? 'N/A' }}</td>
    <td>{{ $payment->payment_method ?? $payment->payment_type ?? 'N/A' }}</td>
    <td>{{ $payment->created_at ? $payment->created_at->format('d M Y, h:i A') : 'N/A' }}</td>
    <td class="fw-medium">₹{{ number_format($payment->amount ?? 0, 2) }}</td>
    <td>
        @php
            $statusClass = match(strtolower($payment->payment_status ?? '')) {
                'completed' => 'badge-success',
                'pending' => 'badge-warning',
                'cancelled' => 'badge-danger',
                'processing' => 'badge-info',
                'failed' => 'badge-danger',
                'refunded' => 'badge-secondary',
                default => 'badge-secondary'
            };
            
            $statusIcon = match(strtolower($payment->payment_status ?? '')) {
                'completed' => 'fa-circle-check',
                'pending' => 'fa-clock',
                'cancelled' => 'fa-circle-xmark',
                'processing' => 'fa-spinner',
                'failed' => 'fa-exclamation-circle',
                'refunded' => 'fa-rotate-left',
                default => 'fa-circle'
            };
        @endphp
        <span class="badge {{ $statusClass }} rounded-pill d-inline-flex align-items-center fs-10">
            <i class="fa-solid {{ $statusIcon }} fs-5 me-1"></i>
            {{ ucfirst($payment->payment_status ?? 'Pending') }}
        </span>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center py-5">
        <div class="text-muted">
            <i class="isax isax-empty-wallet fs-40 mb-3 d-block"></i>
            No payment records found
        </div>
    </td>
</tr>
@endforelse