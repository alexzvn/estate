@push('style')
<style>
    .custom-form label {
        font-weight: bold;
    }

    .custom-form label span{
        color: red;
    }
</style>
@endpush

<!-- create post -->
<div class="modal fade" id="create-post-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đăng tin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form id="create-post-form" class="custom-form">
                    <div class="form-group">
                      <label for="title">Tiêu đề tin <span>*</span></label>
                      <input type="text" class="form-control" name="title" id="title" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Nội dung tin <span>*</span></label>
                        <textarea class="form-control" name="content" id="content" required></textarea>
                    </div>

                    <hr>

                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="phone">Số điện thoại <span>*</span></label>
                              <input type="text" maxlength="15" minlength="9" required
                                class="form-control" name="phone" id="phone" placeholder="Số điện thoại liên hệ">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="price">Khoảng giá <span>*</span></label>
                              <input type="text" required
                                class="form-control" name="price" id="price" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="category">Danh mục <span>*</span></label>
                              <select class="form-control" name="category" id="category" required>
                                <option value="">Chọn danh mục</option>
                                @foreach ($categories as $item)
                                <option style="color: black; font-weight: bold;" disabled>{{ $item->name }}</option>
                                    @foreach ($item->children ?? [] as $item)
                                    <option value="{{ $item->id }}" {{ $item->id === request('categories') ? 'selected' : ''}}>{{ $item->name }}</option>
                                    @endforeach
                                @endforeach
                              </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="create-post-submit">Đăng tin</button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script src="https://cdn.ckeditor.com/ckeditor5/20.0.0/classic/ckeditor.js"></script>
<script>
(function (window) {

    let form = $('#create-post-form');

    $(document).ready(function () {
        $('#create-post-submit').click(function () {
            save().then(res => {
                return res.json();
            }).then(body => {
                console.log(body);
                // form.trigger('reset');
            });
        });

        ClassicEditor
        .create(document.querySelector('#content'))
        .catch( err => {
            console.error( err.stack );
        });
    });

    async function save() {
        return fetch('/create/url', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json'
            },
            body: form.serialize()
        });
    }

}(window));
</script>
@endpush