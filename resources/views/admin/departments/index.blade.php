@extends('layouts.admin')
@section('breadcrumb', 'Departments')
@section('content')
@can('department_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-blue" href="{{ route("admin.departments.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.department.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-body">
        <table class=" table table-bordered compact table-hover ajaxTable datatable datatable-Department">
            <thead>
                <tr style="background-color: #835568; color: white;">
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.status.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.department.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.department.fields.created_at') }}
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
    @can('department_delete')
    let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
    let deleteButton = {
        text: deleteButtonTrans,
        url: "{{ route('admin.departments.massDestroy') }}",
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
        ajax: "{{ route('admin.departments.index') }}",
        columns: [
            { data: 'placeholder', name: 'placeholder' },
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
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
    $('.datatable-Department').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    });

</script>
@endsection