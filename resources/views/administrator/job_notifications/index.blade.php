@extends('administrator.layouts.master')

@include('administrator.'.$prefixView.'.header')

@section('css')

@endsection

@section('content')

    <div class="container-fluid list-products">
        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        @include('administrator.'.$prefixView.'.search')
                    </div>

                    <div class="card-body">

                        @include('administrator.components.checkbox_delete_table')

                        <div class="table-responsive product-table">
                            <table class="table table-hover ">
                                <thead>
                                <tr>
                                    <th><input id="check_box_delete_all" type="checkbox" class="checkbox-parent" onclick="onSelectCheckboxDeleteItem()"></th>
                                    <th>#</th>
                                    <th>Khách hàng</th>
                                    <th>Tiêu đề</th>
                                    <th>Nội dung</th>
                                    <th>Thời gian</th>
                                    <th>Ngày trong tuần</th>
                                    <th>Lặp lại</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="checkbox-delete-item" value="{{$item->id}}">
                                        </td>
                                        <td>{{$item->id}}</td>
                                        <td>
                                            @if($item->userScheduleCron->count() == 0)
                                                Tất cả khách hàng
                                            @else
                                                <ul>
                                                    @foreach($item->userScheduleCron as $itemUserScheduleCron)
                                                        <li>
                                                            {{ optional($itemUserScheduleCron->user)->name}}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                        <td>{{\App\Models\Formatter::getShortDescriptionAttribute($item->title)}}</td>
                                        <td>
                                            {{\App\Models\Formatter::getShortDescriptionAttribute($item->description)}}
                                        </td>
                                        <td>{{$item->time}}</td>
                                        <td>
                                            <ul>
                                                @foreach($item->scheduleCronRepeats as $itemScheduleRepeats)
                                                    <li>
                                                        {{ config('_my_config.days_of_week')[$itemScheduleRepeats->day_of_week]['name'] }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>{{$item->repeat ? 'Có' : 'Không'}}</td>
                                        <td>{{$item->notiable ? 'Bật' : 'Tắt'}}</td>
                                        <td>
                                            <a class="btn btn-outline-secondary btn-sm edit"
                                               data-id="{{$item->id}}">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>

                                            <a href="{{route('administrator.'.$prefixView.'.delete' , ['id'=> $item->id])}}"
                                               data-url="{{route('administrator.'.$prefixView.'.delete' , ['id'=> $item->id])}}"
                                               class="btn btn-outline-danger btn-sm delete action_delete"
                                               title="Delete">
                                                <i class="fa-solid fa-x"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div>
                            @include('administrator.components.footer_table')
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="container-fluid list-products">

        <div class="modal fade" id="modal_add_user" aria-labelledby="modal_add_user" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tạo {{$title}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div>
                                    <label class="col-form-label">Tiêu đề<span
                                            class="text-danger">*</span></label>
                                    <input id="input_title" class="form-control" type="text"/>
                                </div>
                                <div class="mt-3">
                                    <label class="col-form-label">Nội dung<span
                                            class="text-danger">*</span></label>
                                    <textarea id="input_description" class="form-control" rows="5"></textarea>
                                </div>

                                <div class="mt-3">
                                    <label>Chọn khách hàng (Không chọn sẽ gửi tới tất cả khách hàng)</label>
                                    <select id="select_user_id" class="form-control select2_init_multiple" name="parent_id" multiple>
                                        @foreach(\App\Models\User::where('is_admin', 0)->latest()->get() as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label>Đặt ngày</label>
                                @foreach( config('_my_config.days_of_week') as $dayOfWeek)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{$dayOfWeek['id']}}"
                                               id="flexCheckDayOfWeek{{$dayOfWeek['id']}}">
                                        <label class="form-check-label" for="flexCheckDayOfWeek{{$dayOfWeek['id']}}">
                                            {{$dayOfWeek['name']}}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="col-md-3">
                                <div>
                                    <label class="form-label">Thời gian</label>
                                    <input id="input_time" type="time" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="input_repeat">
                                    <label class="form-check-label" for="input_repeat">Lặp lại?</label>
                                </div>
                                <div class="form-check form-switch mt-3">
                                    <input class="form-check-input" type="checkbox" id="input_notiable" checked>
                                    <label class="form-check-label" for="input_notiable">Bật?</label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="text-end">
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary" onclick="confirmAddUser()">Xác nhận</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal_edit_user" aria-labelledby="modal_edit_user" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa {{$title}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div>
                                    <label class="col-form-label">Tiêu đề<span
                                            class="text-danger">*</span></label>
                                    <input id="input_title_edit" class="form-control" type="text"/>
                                </div>

                                <div class="mt-3">
                                    <label class="col-form-label">Nội dung<span
                                            class="text-danger">*</span></label>
                                    <textarea id="input_description_edit" class="form-control" rows="5"></textarea>
                                </div>

                                <div class="mt-3">
                                    <label>Chọn khách hàng (Không chọn sẽ gửi tới tất cả khách hàng)</label>
                                    <select id="select_user_id_edit" class="form-control select2_init_multiple" name="parent_id" multiple>
                                        @foreach(\App\Models\User::where('is_admin', 0)->latest()->get() as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label>Đặt ngày</label>
                                @foreach( config('_my_config.days_of_week') as $dayOfWeek)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{$dayOfWeek['id']}}"
                                               id="flexCheckDayOfWeek{{$dayOfWeek['id']}}_edit">
                                        <label class="form-check-label" for="flexCheckDayOfWeek{{$dayOfWeek['id']}}_edit">
                                            {{$dayOfWeek['name']}}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="col-md-3">
                                <div>
                                    <label class="form-label">Thời gian</label>
                                    <input id="input_time_edit" type="time" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="input_repeat_edit">
                                    <label class="form-check-label" for="input_repeat_edit">Lặp lại?</label>
                                </div>
                                <div class="form-check form-switch mt-3">
                                    <input class="form-check-input" type="checkbox" id="input_notiable_edit" checked>
                                    <label class="form-check-label" for="input_notiable_edit">Bật?</label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="text-end">
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary" onclick="confirmEditUser()">Cập nhật</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script>

        let idEdited, tableRowEdited, table;

        function confirmAddUser() {
            if (!isEmptyInput("input_title", true, "Tiêu đề đang trống", true)
                && !isEmptyInput("input_time", true, "Thời gian đang trống", true)
                && !isEmptyInput("input_description", true, "Nội dung đang trống", true)) {

                let daysOfWeek = [];
                for (let i = 0; i < 7; i++) {
                    if ($('#flexCheckDayOfWeek' + i).is(":checked")) {
                        daysOfWeek.push(i);
                    }
                }

                showLoading()
                callAjax(
                    "POST",
                    "{{route('administrator.'.$prefixView.'.store')}}",
                    {
                        title: $("#input_title").val(),
                        description: $("#input_description").val(),
                        user_id: $("#select_user_id").val(),
                        time: $("#input_time").val(),
                        repeat: isCheckedInput("input_repeat") ? 1 : 0,
                        notiable: isCheckedInput("input_notiable") ? 1 : 0,
                        days_of_week: daysOfWeek,
                    },
                    (response) => {
                        window.location.reload()
                        hideModal("modal_add_user")
                        console.log(response)

                        const days_of_week = @json(config('my_config.days_of_week'));
                        let html = '<ul>';
                        for (let i = 0; i < response.schedule_repeats.length; i++) {
                            html += `<li>${days_of_week[response.schedule_repeats[i].day_of_week].name}</li>`
                        }
                        html += '</ul>';

                        let htmlUsers = "Tất cả khách hàng"
                        if (response.users.length){
                            htmlUsers = "<ul>"

                            for (let i = 0; i < response.users.length; i++) {
                                htmlUsers += `<li>${response.users[i].name}</li>`
                            }

                            htmlUsers += "</ul>"
                        }

                        $('.table-users').DataTable().row.add([
                            response.id,
                            htmlUsers,
                            response.title,
                            response.description,
                            response.time,
                            html,
                            response.repeat ? 'Có' : 'Không',
                            response.notiable ? 'Bật' : 'Tắt',
                            `  <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" data-id="${response.id}">Sửa</a>

                                                <a href="/administrator/{{$prefixViewApi}}/delete/${response.id}" data-url="/administrator/{{$prefixViewApi}}/delete/${response.id}" class="btn btn-danger btn-sm delete">
                                                    Xóa
                                                </a>
                                            </td>`
                        ]).draw();
                    },
                    (error) => {

                    }
                )
            }

        }

        function confirmEditUser() {
            if (!isEmptyInput("input_title_edit", true, "Tiêu đề đang trống", true)
                && !isEmptyInput("input_time_edit", true, "Thời gian đang trống", true)
                && !isEmptyInput("input_description_edit", true, "Nội dung đang trống", true)) {
                showLoading()
                let daysOfWeek = [];
                for (let i = 0; i < 7; i++) {
                    if ($('#flexCheckDayOfWeek' + i + "_edit").is(":checked")) {
                        daysOfWeek.push(i);
                    }
                }

                callAjax(
                    "PUT",
                    "/administrator/{{$prefixViewApi}}/update/" + idEdited,
                    {
                        title: $("#input_title_edit").val(),
                        description: $("#input_description_edit").val(),
                        user_id: $("#select_user_id_edit").val(),
                        time: $("#input_time_edit").val(),
                        repeat: isCheckedInput("input_repeat_edit") ? 1 : 0,
                        notiable: isCheckedInput("input_notiable_edit") ? 1 : 0,
                        days_of_week: daysOfWeek,
                    },
                    (response) => {
                        window.location.reload()
                        hideModal("modal_edit_user")

                        console.log(response)

                        const days_of_week = @json(config('my_config.days_of_week'));
                        let html = '<ul>';
                        for (let i = 0; i < response.schedule_repeats.length; i++) {
                            html += `<li>${days_of_week[response.schedule_repeats[i].day_of_week].name}</li>`
                        }
                        html += '</ul>';


                        let htmlUsers = "Tất cả khách hàng"
                        if (response.users.length){
                            htmlUsers = "<ul>"

                            for (let i = 0; i < response.users.length; i++) {
                                htmlUsers += `<li>${response.users[i].name}</li>`
                            }

                            htmlUsers += "</ul>"
                        }

                        table.row(tableRowEdited).data([
                            response.id,
                            htmlUsers,
                            response.title,
                            response.description,
                            response.time,
                            html,
                            response.repeat ? 'Có' : 'Không',
                            response.notiable ? 'Bật' : 'Tắt',
                            `  <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" data-id="${response.id}">Sửa</a>

                                                <a href="/administrator/{{$prefixViewApi}}/delete/${response.id}" data-url="/administrator/{{$prefixViewApi}}/delete/${response.id}" class="btn btn-danger btn-sm delete">
                                                    Xóa
                                                </a>
                                            </td>`,
                        ]).draw();

                    },
                    (error) => {

                    }
                )
            }

        }

        function editUser(id, tableRow = null) {

            idEdited = id
            tableRowEdited = tableRow

            callAjax(
                "GET",
                "/administrator/{{$prefixViewApi}}/" + id,
                {},
                (response) => {
                    showModal("modal_edit_user")
                    console.log(response)

                    let users = []
                    for(let i = 0 ; i < response.users.length;i++){
                        users.push(response.users[i].id)
                    }

                    $('#input_title_edit').val(response.title)
                    $('#input_description_edit').val(response.description)
                    $('#select_user_id_edit').val(users).trigger('change');
                    $('#input_time_edit').val(response.time)

                    if (response.repeat) {
                        $('#input_repeat_edit').prop('checked', true)
                    } else {
                        $('#input_repeat_edit').prop('checked', false)
                    }

                    if (response.notiable) {
                        $('#input_notiable_edit').prop('checked', true)
                    } else {
                        $('#input_notiable_edit').prop('checked', false)
                    }

                    for (let i= 0 ; i < 7; i++){
                        $('#flexCheckDayOfWeek' + i + "_edit").prop('checked', false)
                    }

                    response.schedule_repeats.forEach((item, index) =>{
                        $('#flexCheckDayOfWeek' + item.day_of_week + "_edit").prop('checked', true)
                    });

                },
                (error) => {

                }
            )
        }

        $(document).ready(function () {
            table = $('.table-users').DataTable();
            $('.table-users tbody').on('click', 'a.delete', function (e) {
                event.preventDefault()
                actionDelete(e, $(this).data('url'), table, $(this).parents('tr'))
            });

            $('table tbody').on('click', 'a.edit', function (e) {
                event.preventDefault()
                var tableRow = table.row($(this).parents('tr'));
                editUser($(this).data('id'), tableRow)
            });
        });

    </script>
@endsection

