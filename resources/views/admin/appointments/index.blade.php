@extends('layouts.admin')
@section('breadcrumb', 'Appointments')
@section('content')
@can('appointment_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-blue" href="{{ route("admin.appointments.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.appointment.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-body">
        <table class=" table table-bordered compact table-hover ajaxTable datatable datatable-Appointment">
            <thead>
                <tr style="background-color: #835568; color: white;">
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.appointment.fields.client') }}
                    </th>
                    <th>
                        {{ trans('cruds.appointment.fields.employee') }}
                    </th>
                    <th>
                        {{ trans('cruds.appointment.fields.start_time') }}
                    </th>
                    <th>
                        {{ trans('cruds.appointment.fields.finish_time') }}
                    </th>
                    <th>
                        {{ trans('cruds.appointment.fields.comments') }}
                    </th>
                    <th>
                        {{ trans('cruds.appointment.fields.status') }}
                    </th>
                    <th>
                        {{ trans('cruds.appointment.fields.created_at') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>


    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
    @can('appointment_delete')
    let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
    let deleteButton = {
        text: deleteButtonTrans,
        url: "{{ route('admin.appointments.massDestroy') }}",
        className: 'btn-danger',
        action: function (e, dt, node, config) {
        var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
            return entry.id
        });

        if (ids.length === 0) {
            alert('{{ trans('global.datatables.zero_selected') }}')

            return
        }

        if (confirm('{{ trans('global.areYouSure') }}')) {
            $.ajax({
            headers: {'x-csrf-token': _token},
            method: 'POST',
            url: config.url,
            data: { ids: ids, _method: 'DELETE' }})
            .done(function () { location.reload() })
        }
        }
    }
    dtButtons.push(deleteButton)
    @endcan

    let dtOverrideGlobals = {
        buttons: dtButtons,
        processing: true,
        serverSide: true,
        retrieve: true,
        aaSorting: [],
        ajax: "{{ route('admin.appointments.index') }}",
        columns: [
            { data: 'placeholder', name: 'placeholder' },
            { data: 'client_name', name: 'client.name' },
            { data: 'employee_name', name: 'employee.name' },
            {
                data: 'start_time',
                name: 'start_time',
                render: function (data, type, row) {
                    if (!data) return '';
                    let date = new Date(data);
                    let day = String(date.getDate()).padStart(2, '0');
                    let month = String(date.getMonth() + 1).padStart(2, '0');
                    let year = date.getFullYear();

                    let hours = date.getHours();
                    let minutes = String(date.getMinutes()).padStart(2, '0');
                    let ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12;
                    hours = hours ? hours : 12;

                    return `${day}/${month}/${year} ${hours}:${minutes} ${ampm}`;
                }
            },
            {   
                data: 'finish_time', 
                name: 'finish_time',
                render: function (data, type, row) {
                    if (!data) return '';
                    let date = new Date(data);
                    let day = String(date.getDate()).padStart(2, '0');
                    let month = String(date.getMonth() + 1).padStart(2, '0');
                    let year = date.getFullYear();

                    let hours = date.getHours();
                    let minutes = String(date.getMinutes()).padStart(2, '0');
                    let ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12;
                    hours = hours ? hours : 12;

                    return `${day}/${month}/${year} ${hours}:${minutes} ${ampm}`;
                } 
            },
            { data: 'comments', name: 'comments' },
            { 
                data: 'status', 
                name: 'status.name',
                render: function (data, type, row) {
                    let html = data ? data : '';
                    // Show info icon if status is 'Cancelled' or 'Rejected' and cancel_reason exists
                    if (
                        (data && (data.toLowerCase().includes('canceled') || data.toLowerCase().includes('reject'))) &&
                        row.cancel_reason
                    ) {
                        html += ` <a href="#" class="show-reason-cancel" data-reason="${encodeURIComponent(row.cancel_reason)}">
                                    <i class="fa fa-info-circle text-info" title="Show reason"></i>
                                  </a>`;
                    } else if (
                        (data && data.toLowerCase().includes('completed')) &&
                        row.completed_reason
                    ) {
                        html += ` <a href="#" class="show-reason-completed" data-reason="${encodeURIComponent(row.completed_reason)}">
                                    <i class="fa fa-info-circle text-info" title="Show reason"></i>
                                  </a>`;
                    }
                    // Modal HTML (only add once)
                    if (!$('#cancelReasonModal').length) {
                        $('body').append(`
                            <div class="modal fade" id="cancelReasonModal" tabindex="-1" role="dialog" aria-labelledby="cancelReasonModalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="cancelReasonModalLabel">Cancel/Reject Reason</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body"></div>
                                </div>
                              </div>
                            </div>
                        `);
                        // Attach click handler once
                        $(document).on('click', '.show-reason-cancel', function(e) {
                            e.preventDefault();
                            let reason = decodeURIComponent($(this).data('reason'));
                            $('#cancelReasonModal .modal-body').text(reason);
                            $('#cancelReasonModal').modal('show');
                        });
                    }

                    if (!$('#completedReasonModal').length) {
                        $('body').append(`
                            <div class="modal fade" id="completedReasonModal" tabindex="-1" role="dialog" aria-labelledby="completedReasonModalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="completedReasonModalLabel">Completed Reason</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body"></div>
                                </div>
                              </div>
                            </div>
                        `);
                        // Attach click handler once
                        $(document).on('click', '.show-reason-completed', function(e) {
                            e.preventDefault();
                            let reason = decodeURIComponent($(this).data('reason'));
                            $('#completedReasonModal .modal-body').text(reason);
                            $('#completedReasonModal').modal('show');
                        });
                    }
                    return html;
                }
            },
            {
                data: 'created_at',
                name: 'created_at',
                render: function (data, type, row) {
                    if (!data) return '';
                    let date = new Date(data);
                    let day = String(date.getDate()).padStart(2, '0');
                    let month = String(date.getMonth() + 1).padStart(2, '0');
                    let year = date.getFullYear();
                    return `${day}/${month}/${year}`;
                }
            },
            { data: 'actions', name: '{{ trans('global.actions') }}' }
        ],
        order: [[ 1, 'desc' ]],
        pageLength: 100,
    };
    $('.datatable-Appointment').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    });

</script>
@endsection