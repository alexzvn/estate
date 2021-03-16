@extends('dashboard.app')

@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/plugins/select2/select2.min.css') }}">
@endpush

@section('content')

<div class="col-md-5">
    <div class="statbox widget box shadow-none mb-3">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>Gửi tin nhắn</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
           <form action="{{ route('manager.sms.send') }}" method="post">
                @csrf
                <div class="form-group input-group-sm">
                    <label for="recipient">Số điện thoại nhận</label>
                    <select class="form-control tagging" name="recipients" multiple="multiple" required>
                    @foreach ($recipients as $phone)
                        <option value="{{ $phone }}" selected>{{ $phone }}</option>
                    @endforeach
                    </select>
                </div>

                <div class="form-group input-group-sm">
                  <label for="template">Chọn mẫu gửi</label>
                  <select class="form-control" id="template">
                      <option value="">Chọn mẫu gửi</option>
                    @foreach ($templates as $template)
                        <option value="{{ $template->content }}">{{ $template->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                    <label for="message">Nội dung</label>
                    <textarea class="form-control" name="message" id="message" rows="4" placeholder="Nội dung của tin nhắn?"></textarea>
                    <span class="text-danger" id="message-length"></span>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-success">Gửi</button>
                </div>

           </form>
        </div>
    </div>
</div>

<div class="col-md-7">
    <div class="statbox widget box box-shadow mb-3">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>
                        Danh sách mẫu tin
                        <a href="{{ route('manager.sms.template.create') }}" class="btn btn-success rounded-circle"><i data-feather="plus"></i></a>
                    </h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <table class="table table-hover table-light mb-4">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>tên mẫu</th>
                        <th>Nội dung</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($templates as $item)
                    <tr>
                        <td>{{ $item->iteration }}</td>
                        <th>{{ $item->name }}</th>
                        <th>{{ $item->content }}</th>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


@push('script')
<script src="{{ asset('dashboard/plugins/select2/select2.min.js') }}"></script>
<script>
(function () {

    const ss = $(".tagging").select2({
        tags: true,
    })

    const checker = () => {
        const content = $('#message').val(),
            isAscii = /^[\x00-\x7F]*$/.test(content),
            maxLength = isAscii ? 160 : 70;

        if (content.length > maxLength) {
            $('#message-length').html(`Chiều dài tin nhắn vượt quá ${maxLength} ký tự`)
        } else {
            $('#message-length').html(`${content.length}/${maxLength}`)
        }
    }

    $('#template').on('change', function() {
        const content = $(this).val()

        content.length && $('#message').val(content)

        checker();
    })

    $('#message').on('keyup', checker)

}())
</script>
@endpush
