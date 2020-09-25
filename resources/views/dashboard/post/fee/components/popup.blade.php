@php
use App\Enums\PostType;
@endphp

<!-- post edit modal -->
<div class="modal fade" id="post-edit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa bài viết</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form id="post-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="post-id" name="id" value="">

                    <div class="form-row">
                        <div class="col-md-8">
                            <div class="form-group input-group-sm">
                                <label for="post-title">Tiêu đề</label>
                                <input type="text"
                                class="form-control" value="" name="title" id="post-title" aria-describedby="title" placeholder="Tiêu đề tin" required>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <div class="form-group input-group-sm">
                                <label for="post-type">Loại Tin</label>
                                <select class="form-control" name="type" id="post-type">
                                  <option value="">Trống</option>
                                  @foreach (PostType::getValues() as $name)
                                  <option value="{{ $name }}">{{ $name }}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <div class="form-group input-group-sm">
                                <label for="post-status">Trạng thái</label>
                                <select class="form-control" name="status" id="post-status">
                                  <option value="0">Bản nháp</option>
                                  <option value="1">Chờ duyệt</option>
                                  <option value="2">Xuất bản</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">

                        <div class="col-md-4 col-sm-6">
                            <div class="form-group input-group-sm">
                              <label for="post-price">Giá tiền</label>
                              <input type="text"
                                class="form-control" value="" name="price" id="post-price" placeholder="Giá tin" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group input-group-sm">
                              <label for="post-commission">Hoa Hồng</label>
                              <input type="text"
                                class="form-control" value="" name="commission" id="post-commission" placeholder="" step="1" value="" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group input-group-sm">
                                <label for="post-phone">Số điện thoại</label>
                                <input type="text"
                                  class="form-control" value="" name="phone" id="post-phone" placeholder="0355...." required>
                              </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="form-group input-group-sm">
                                <label for="post-category">Danh mục</label>
                                <select class="form-control" name="category_ids[]" id="post-category">
                                <option value="">Chọn danh mục</option>
                                @php
                                    $catId = $category->id ?? null;
                                @endphp
                                  @foreach ($categories as $item)
                                    @if (!$item->children || count($item->children) < 1)
                                        <option value="{{ $item->id }}" {{ $item->id == $catId ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @else
                                        <option value="{{ $item->id }}" disabled style="font-weight: bold; color: #0e1726;"><strong> {{ $item->name }} </strong></option>
                                        @foreach ($item->children as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == $catId ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    @endUnless
                                  @endforeach
                                </select>
                            </div>
                        </div>
    
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group input-group-sm">
                                <label for="post-province">Tỉnh, thành phố</label>
                                <select class="form-control" name="province" id="post-province">
                                    <option value="" selected>Trống</option>
                                    @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
    
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group input-group-sm">
                                <label for="post-district">Quận/huyện</label>
                                <select class="form-control" name="district" id="post-district">
                                    <option value="" selected>Trống</option>
                                    @isset($activeProvince)
                                    @foreach ($activeProvince->districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="post-content">Nội dung</label>
                        <textarea class="form-control" name="content" id="post-content" rows="3"></textarea>
                    </div>
                    
                    <div>
                        <p class="text-muted m-0">Ngày cập nhật cuối cùng là <span class="text-info"></span>, đăng bởi
                    </div>

                    <div id="sync-file-ids"></div>

                    <div class="widget-content widget-content-area">
                        <div class="custom-file-container" data-upload-id="mySecondImage">
                            <label>Chọn ảnh đại diện <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                            <label class="custom-file-container__custom-file" >
                                <input type="file" name="images[]" accept="image/*" class="custom-file-container__custom-file__custom-file-input" multiple>
                                <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                                <span class="custom-file-container__custom-file__custom-file-control"></span>
                            </label>
                            <div class="custom-file-container__image-preview"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="post-save" type="button" class="btn btn-primary">Lưu</button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script src="https://cdn.ckeditor.com/ckeditor5/20.0.0/classic/ckeditor.js"></script>
<script src="{{ asset('dashboard/plugins/input-mask/jquery.inputmask.bundle.min.js') }}"></script>
<script>
(function (window) {
    $(document).ready(function () {
        $('#post-price').inputmask({
            alias: 'currency',
            prefix: '',
            digits: 0,
            rightAlign: false
        });

        $('#post-save').click(function () {
            let id = $('#post-id').val();

            fetch(`/manager/post/fee/${id}/update`,{
                headers: {
                    'Accept': 'application/json'
                },
                redirect: 'manual',
                method: 'POST',
                body: serializeBody()
            }).then(res => {
                if (res.status !== 0) {
                    return res.json()
                }

                return {success: true, data: 'Đã lưu lại tin này'};

            }).then(notify);
        });
    });

    function serializeBody() {
        let form = new FormData(document.getElementById('post-form'));

        form.append('content', editor.getData());

        return form;
    }

    function notify(data) {
        if (data.success) {
            Snackbar.show({
                text: 'Success',
                actionTextColor: '#fff',
                backgroundColor: '#8dbf42',
                text: data.data,
                pos: 'bottom-right',
            });

            return $('#post-edit').modal('hide');
        }

        let alertContext;

        for (const key in data.errors) {
            alertContext = data.errors[key][0]; break;
        }

        Snackbar.show({
            text: 'Danger',
            actionTextColor: '#fff',
            backgroundColor: '#e7515a',
            text: alertContext,
            pos: 'bottom-right',
            duration: 5000,
            showAction: false
        });
    }
}(window))
</script>
@endpush

@push('script')
<script>
$(document).ready(() => {
    let data = JSON.parse('@json($provinces)');

    window.address = {
        setDistricts(provinceId) {
            let province = data.filter((e) => {return e._id === provinceId})[0];
            let district = $('#post-district');

            district.html('');
            district.append('<option value="" selected>Chọn</option');

            if (province === undefined) return;

            province.districts.map((e) => {
                district.append(`<option value="${e._id}">${e.name}</option`);
            });
        }
    };

    $('#post-province').on('change', () => {
        address.setDistricts($('#post-province').val());
    });
});
</script>
@endpush