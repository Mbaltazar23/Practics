<?php headerAdmin($data); ?>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="title">
                            <h4>Ajustes del Perfil</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= base_url(); ?>/dashboard">Inicio</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Perfil</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
                    <div class="pd-20 card-box height-100-p">
                        <div class="profile-photo">
                            <img src="<?= $_SESSION["userData"]["imgAvatar"] ?>" alt="<?= $_SESSION["userData"]["avatar"] ?>" class="avatar-photo">
                        </div>
                        <h5 class="text-center h5 mb-0"><?= $_SESSION["userData"]["nombres"] ?></h5>
                        <p class="text-center text-muted font-14">Registrado desde <?= darFormatoFecha($_SESSION["userData"]["fecha"]) ?></p>
                        <div class="profile-info">
                            <h5 class="mb-20 h5 text-blue">Contact Information</h5>
                            <ul>
                                <li>
                                    <span>Email Address:</span>
                                    FerdinandMChilds@test.com
                                </li>
                                <li>
                                    <span>Phone Number:</span>
                                    619-229-0054
                                </li>
                                <li>
                                    <span>Country:</span>
                                    America
                                </li>
                                <li>
                                    <span>Address:</span>
                                    1807 Holden Street<br>
                                    San Diego, CA 92115
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
                    <div class="card-box height-100-p overflow-hidden">
                        <div class="profile-tab height-100-p">
                            <div class="tab height-100-p">
                                <ul class="nav nav-tabs customtab" role="tablist">

                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" role="tab">Ajustes</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <!-- Setting Tab start -->
                                    <div class="tab-pane show active fade height-100-p" id="setting" role="tabpanel">
                                        <div class="profile-setting">
                                            <form id="formPerfil">
                                                <ul class="profile-edit-list row">
                                                    <li class="weight-500 col-md-10">
                                                        <h4 class="text-blue h5 mb-20">Actualice sus datos personales</h4>
                                                        <input type="hidden" name="idPerPass" id="idPerPass"/>
                                                        <div class="form-group">
                                                            <label>Full Name</label>
                                                            <input class="form-control" type="text">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Title</label>
                                                            <input class="form-control form-control-lg" type="text">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input class="form-control form-control-lg" type="email">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Date of birth</label>
                                                            <input class="form-control form-control-lg date-picker" type="text">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Gender</label>
                                                            <div class="d-flex">
                                                                <div class="custom-control custom-radio mb-5 mr-20">
                                                                    <input type="radio" id="customRadio4" name="customRadio" class="custom-control-input">
                                                                    <label class="custom-control-label weight-400" for="customRadio4">Male</label>
                                                                </div>
                                                                <div class="custom-control custom-radio mb-5">
                                                                    <input type="radio" id="customRadio5" name="customRadio" class="custom-control-input">
                                                                    <label class="custom-control-label weight-400" for="customRadio5">Female</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Country</label>
                                                            <select class="selectpicker form-control form-control-lg" data-style="btn-outline-secondary btn-lg" title="Not Chosen">
                                                                <option>United States</option>
                                                                <option>India</option>
                                                                <option>United Kingdom</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>State/Province/Region</label>
                                                            <input class="form-control form-control-lg" type="text">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Postal Code</label>
                                                            <input class="form-control form-control-lg" type="text">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Phone Number</label>
                                                            <input class="form-control form-control-lg" type="text">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Address</label>
                                                            <textarea class="form-control"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Visa Card Number</label>
                                                            <input class="form-control form-control-lg" type="text">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Paypal ID</label>
                                                            <input class="form-control form-control-lg" type="text">
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="custom-control custom-checkbox mb-5">
                                                                <input type="checkbox" class="custom-control-input" id="customCheck1-1">
                                                                <label class="custom-control-label weight-400" for="customCheck1-1">I agree to receive notification emails</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mb-0">
                                                            <input type="submit" class="btn btn-primary" value="Update Information">
                                                        </div>
                                                    </li>
                                                </ul>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- Setting Tab End -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-wrap pd-20 mb-20 card-box">
            DeskApp - Bootstrap 4 Admin Template By <a href="https://github.com/dropways" target="_blank">Ankit Hingarajiya</a>
        </div>
    </div>
</div>

<?php footerAdmin($data); ?>
