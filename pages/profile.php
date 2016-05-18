<?php

if(!defined("SERVLET"))
    die("You may not view this page.");

// If the user is logged in
if(isset($_SESSION["user"])){

    // First of all see if they have been sent via the login page
    // So if loginaccount is in the URL
    if(strpos($_SERVER["REQUEST_URI"], "?action=loginaccount") !== false){
        // Then redirect them to the actual profile page
        // Comment this line out to enable debugging on that page
        header("Location: index.php?action=profile");
    }

    // Load the Dashboard header
    require_once "pages/templates/headerDashboard.php";

    if(isset($_GET["username"])){
        /**
         * THIS SECTION WILL SHOW SOMEBODY ELSE'S PROFILE INSTEAD OF THE USER'S
         * NO CHANGE OPTIONS HERE ETC.
         */
    }else{
        /**
         * THIS SECTION WILL SHOW THE USER'S PROFILE
         */
        // Vars that will be used throughout
        $username = $_SESSION["user"]->getUsername();
        $profilePicture = "images/$username-profile-cropped.jpg";

        if(file_exists($profilePicture)){
            $profilePictureExists = true;
        }else{
            $profilePictureExists = false;
        }
        ?>

        <div class="row placeholders">
            <div class="col-xs-6 col-sm-3 placeholder">
                <img src="<?php echo $profilePictureExists ? $profilePicture : "" ?>" width="150" height="150" class="img-responsive" alt="Profile Picture" id="profile-picture">
                         <h4>Profile Picture</h4>
                         <span class="text-muted">
                             <a class="hover-pointer" data-toggle="modal" data-target="#profile-picture-modal">
                Change Picture
            </a>
                         </span>
                     </div>
                 </div>

                <div class="jumbotron dashboard-title">
                    <h3>
            My Profile
            </h3>
                </div>

                <h2 class="sub-header">Your Information</h2>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Username</th>
                            <th>Password</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?php echo $_SESSION["user"]->getUsername(); ?></td>
                                <td>
                                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#password-modal">
                                        Change Password
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email Address</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?php echo $_SESSION["user"]->getFirstName(); ?></td>
                        <td><?php echo $_SESSION["user"]->getLastName(); ?></td>
                        <td><?php echo $_SESSION["user"]->getEmail(); ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Password Modal -->
            <div id="password-modal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Change your password</h4>
                        </div>
                        <div class="modal-body">
                            <form id="changepassword">
                                <label for="oldpassword" class="sr-only">Old Password</label>
                                <input name="oldpassword" id="oldpassword" type="password" class="form-control" placeholder="Old Password" autocomplete="off" required autofocus>
                                <br />
                                <label for="newpassword" class="sr-only">New Password</label>
                                <input name="newpassword" id="newpassword" type="password" class="form-control" placeholder="New Password" autocomplete="off" required>
                                <br />
                                <label for="repeatnewpassword" class="sr-only">Repeat New Password</label>
                                <input name="repeatnewpassword" id="repeatnewpassword" type="password" class="form-control" placeholder="Repeat New Password" autocomplete="off" required>
                                <br />
                                <button class="btn btn-default btn-primary btn-block" type="submit">Change Password</button>
                            </form>
                        </div>
                        <div id="password-result"></div>
                        <div id="password-result-negative"></div>
                        <div class="modal-footer">
                            <button type="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Profile picture change Modal -->
            <div id="profile-picture-modal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Change your Profile Picture</h4>
                        </div>
                        <div class="modal-body text-center">
                            <label for="image-select" class="sr-only">Pick an Image</label>
                            <input type="file" id="image-select" class="form-control" placeholder="Pick an Image" required>
                            <br />
                            <button id="image-upload" class="btn btn-default btn-primary btn-block">Crop this Image</button>
                            <br />
                            <img src="" id="image-profile" width="300px" style="display: none"/>
                            <br />
                            <div id="coords" class="hidden"></div>
                            <div id="coords2" class="hidden"></div>
                            <button id="send-coords" style="display: none">Change my Profile Picture!</button>
                        </div>
                        <div class="modal-footer">
                            <button type="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Context Menus -->

            <!-- Profile Picture Context Menu -->
            <ul class="_contextmenu" id="_contextmenu-profile-picture">
                <li data-action="change-profile-picture">Change Profile Picture</li>
                <li data-action="full-profile-picture">See full-sized image</li>
                <li data-action="cropped-profile-picture">See cropped image</li>
            </ul>

    <?php
    }

    // Load the Dashboard footer
    require_once "pages/templates/footerDashboard.php";

    // Otherwise, the user is not logged in, so send them to the homepage
    }else{
        header("Location: index.php");
    }