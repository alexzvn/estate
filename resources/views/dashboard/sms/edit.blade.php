@extends('dashboard.app')

@section('content')
<div class="col-lg-12">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">
            <div class="statbox widget box shadow-none mb-3">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>
                                Sửa mẫu sms
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                   <form action="{{ route('manager.sms.template.update', $template) }}" method="post">
                    @csrf
                       <div class="form-group input-group-sm">
                         <label for="name">Tên danh mục</label>
                         <input type="text"
                           class="form-control" value="{{ $template->name }}" name="name" id="name" placeholder="Tên mẫu sms" required>
                       </div>
                       <div class="form-group">
                         <label for="message">Mô tả</label>
                         <textarea class="form-control" name="message" id="message" rows="3" placeholder="Nội dung tin nhắn">{{ $template->content }}</textarea>
                         <span class="text-danger" id="message-length"></span>
                        </div>

                       <div>
                            <button id="delete" type="button" class="btn btn-danger float-left">Xóa</button>
                            <button type="submit" class="btn btn-primary float-right">Cập nhật</button>
                       </div>

                   </form>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="delete-form" class="d-none" action="{{ route('manager.sms.template.delete', ['SmsTemplate' => $template]) }}" method="post">@csrf</form>
@endsection

@push('script')
<script>
$('#delete').click(function (e) {
    if (confirm('Bạn có muốn xóa danh mục này?')) {
        document.getElementById('delete-form').submit();
    }
});

$('#message').on('keyup', function () {
    const content = $(this).val(),
        isAscii = /^[\x00-\x7F]*$/.test(content),
        maxLength = isAscii ? 160 : 70;

    if (content.length > maxLength) {
        $('#message-length').html(`Chiều dài tin nhắn vượt quá ${maxLength} ký tự`)
    } else {
        $('#message-length').html(`${content.length}/${maxLength}`)
    }
});
</script>
@endpush
