@if (($view ?? null) === 'browse')
    <p>{{ $data->{$row->field} }}</p>
@elseif (($view ?? null) === 'read')
    <p>{{ $dataTypeContent->{$row->field} }}</p>
@else
    <select class="form-control" name="{{ $row->field }}">
        @foreach ($options as $value => $label)
            <option value="{{ $value }}" {{ $dataTypeContent->{$row->field} === $value ? 'selected="selected"' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @if ($row->field === 'merch_status')
        <input type="hidden" name="send_shipping_notification" value="" />

        <div class="modal modal-order-status fade modal-warning" id="confirm_send_shipping_email_modal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><i class="voyager-question"></i> Send email?</h4>
                    </div>

                    <div class="modal-body">
                        <h4>Would you like to send an email notification to this customer informing them that their order has shipped?</h4>
                    </div>

                    <div class="modal-footer" style="display: flex; flex-direction: column; align-items: flex-end;">
                        <button type="button" class="btn btn-secondary" style="margin: 0 0 10px 0;" id="cancel_shipping_status_change">Cancel</button>
                        <button type="button" class="btn btn-warning" style="margin: 0 0 10px 0;" id="do_not_send_shipping_email">Mark as shipped but do not send an email</button>
                        <button type="button" class="btn btn-danger" style="margin: 0 0 10px 0;" id="send_shipping_email">Mark as shipped and send email to customer when I save this order</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('javascript')
        <script>
            @if ($row->field === 'merch_status' && !$dataTypeContent->shipping_notification_sent_at && $dataTypeContent->{$row->field} !== \App\Models\Order::STATUS_SHIPPED && !($dataTypeContent->user && !$dataTypeContent->user->notification_pref_orders))
                $('document').ready(function () {
                    $('select[name="merch_status"]').on('change', function(event) {
                        if (event.target.value === '{{ \App\Models\Order::STATUS_SHIPPED }}') {
                            $('#confirm_send_shipping_email_modal').modal('show');
                        }
                    });

                    $('#cancel_shipping_status_change').on('click', function() {
                        $('select[name="merch_status"]').val('{{ $dataTypeContent->{$row->field} }}');
                        $('input[name="send_shipping_notification"]').val('');

                        $('#confirm_send_shipping_email_modal').modal('hide');
                    });

                    $('#do_not_send_shipping_email').on('click', function() {
                        $('input[name="send_shipping_notification"]').val('0');

                        $('#confirm_send_shipping_email_modal').modal('hide');
                    });

                    $('#send_shipping_email').on('click', function() {
                        $('input[name="send_shipping_notification"]').val('1');

                        $('#confirm_send_shipping_email_modal').modal('hide');
                    });
                });
            @endif
        </script>
    @endpush
@endif
