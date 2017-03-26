<?php require 'application/views/layouts/header.php'; ?>
<div class="container">
    <ul class="nav nav-tabs" role="tablist" data-tabs="tabs">
        <li class="nav-item">
            <a data-toggle="tab" class="nav-link active"  data-target="#login" role="tab">Log In</a>
        </li>
        <li class="nav-item">
            <a data-toggle="tab" class="nav-link" data-target="#signup" role="tab">Sign Up</a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="login" class="tab-pane active" role="tabpanel">
            <br>
            <form>
                <div class="form-group row">
                    <div class="col-6">
                        <input type="text" class="form-control" id="username" placeholder="Username">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-6">
                        <input type="password" class="form-control" id="password" placeholder="Password">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Log In</button>
                </div>
            </form>
        </div>
        <div id="signup" class="tab-pane" role="tabpanel">
            <br>
            <form>
                <div class="form-group row">
                    <div class="col-3">
                        <input type="text" class="form-control" id="firstname" placeholder="First Name">
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control" id="lastname" placeholder="Last Name">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-6">
                        <input type="email" class="form-control" id="email" placeholder="Email">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-6">
                        <input type="text" class="form-control" id="username" placeholder="Username">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-6">
                        <input type="password" class="form-control" id="password" placeholder="Password">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-6">
                        <select class="form-control" id="gender">
                            <option value="" disabled selected>Gender</option>
                            <option>Female</option>
                            <option>Male</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-6">
                        <input type="text" class="form-control" id="address1" placeholder="Address 1">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-6">
                        <input type="text" class="form-control" id="address2" placeholder="Address 2">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-2">
                        <input type="text" class="form-control" id="city" placeholder="City">
                    </div>
                    <div class="col-2">
                        <input type="text" class="form-control" id="state" placeholder="State">
                    </div>
                    <div class="col-2">
                        <input type="text" class="form-control" id="zip" placeholder="Zip">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require 'application/views/layouts/footer.php'; ?>
