<div id="login-form" class="popup mfp-hide">
    <header class="popup-heading">
        <h2>Log In</h2>
        <small>You can use your email address to log in</small>
    </header>
    <div class="popup-inner">
        <form autocomplete="off" id="login">
            <div id="error-msg"></div>
            <div class="row">
                <div class="col col-lg-6">
                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" name="user_email" id="user_email" class="form-control" tabindex="1"
                            required>
                    </div>
                </div>
                <div class="col col-lg-6">
                    <div class="form-group">
                        <label for="password">Password <span class="required">*</span></label>
                        <input type="password" name="user_password" id="user_password" class="form-control" tabindex="2"
                            required>
                    </div>
                </div>
                <div class="col col-lg-6">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" value="remember" tabindex="3">
                                Remember me
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col col-lg-6">
                    <div class="form-group">
                        <a href="#forget-password-form" class="btn-popup">Forget Password ?</a>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-lg btn-primary">Log In</button>
                </div>
                <p class="text-center mt-10">
                    Not registered? <a class="btn-popup" href="#signup-form">Sign Up Here</a>
                </p>
            </div>
        </form>
    </div>
</div>