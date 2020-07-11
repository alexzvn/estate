@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row bg-white p-2 shadow rounded">
        <div class="col-md-12">
            <div class="border rounded">
                <ul class="nav nav-tabs nav-custom-tabs mx-3" id="myTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#section-1" role="tab">Home</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#section-2" role="tab">Profile</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="contact-tab" data-toggle="tab" href="#section-3" role="tab">Contact</a>
                    </li>
                </ul>
                <div class="border-top p-3" style="background-color: rgb(223, 223, 223);">
                    <div class="form-row">

                        <div class="col-1">
                            <button class="btn btn-light btn-lg w-100"><i class="fas fa-filter"></i></button>
                        </div>
                        <div class="col-11">
                            <div class="input-group">
                                <input type="text" style="border: solid #3490dc;" class="form-control form-control-lg border-right-0" placeholder="Tìm kiếm thông tin trên website">
                                <div class="input-group-append">
                                    <button class="btn btn-lg btn-primary" type="button">Tìm kiếm</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-9" id="myTabContent">
                <div class="tab-content" >
                    <div class="tab-pane fade show active" id="section-1" role="tabpanel">
                        <table class="table table-hover">
                            <thead>
                              <tr>
                                <th scope="col" width="6%">TT</th>
                                <th scope="col" width="75%">Tiêu đề</th>
                                <th scope="col">Giá</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach (range(1, 20) as $item)
                              <tr>
                                <th class="text-muted" scope="row">{{ $loop->iteration }}</th>
                                <td>
                                    <p class="mb-0"><strong>Integer ultrices enim aliquam ultrices varius. In hac habitasse platea dictumst.</strong> <br>
                                    <span class="text-muted">
                                        <strong>Loại: </strong> Cho thuê chung cư
                                        - Mỹ đình
                                        - Ngày 03/07/2019
                                    </span>
                                    </p>
                                </td>
                                <td>Otto</td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                    </div>
                    <div class="tab-pane fade" id="section-2" role="tabpanel">

                    </div>
                    <div class="tab-pane fade" id="section-3" role="tabpanel">

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-2 text-justify" style="background-color: aliceblue; font-size: 17px; font-family; border-top: 4px solid #9ce8d9 !important;">
                    <h5 class="text-center text-uppercase">Thông Báo</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quis elit tincidunt, sagittis dolor eget, semper nunc. Phasellus dapibus feugiat odio, non molestie eros placerat at.</p>
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection

@push('style')
<style>
.nav-custom-tabs {
    border: 0;
}

.nav-custom-tabs .nav-item .nav-link {
    text-transform: uppercase;
    background: none;
    color: gray;
    border: 0;
}

.nav-custom-tabs .nav-item .nav-link.active {
    color: black;
    font-weight: bolder;
    border-bottom: solid 3px #3e92cc;
}

</style>
@endpush