<?php
if(!defined("SERVLET"))
    die("You may not view this page.");
if(isset($_SESSION["user"])){
    // First of all see if they have been sent via the login page
    if(strpos($_SERVER["REQUEST_URI"], "?action=loginaccount") !== false){
        // Comment this line out to enable debugging on that page
        header("Location: index.php?action=profile");
    }
    // Load the Dashboard header
    require_once "pages/templates/headerdashboard.php";
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
        ?>

        <div class="row placeholders">
            <div class="col-xs-6 col-sm-3 placeholder">
                <img src="<?php echo $_SESSION["user"]->getAvatar() . "-cropped.jpg" ?>" width="150" height="150" class="img-responsive" alt="Profile Picture" id="profile-picture">
                <br><br>
                <span class="text-muted">
                    <a class="hover-pointer" data-toggle="modal" data-target="#profile-picture-modal">
                        <button type="button" class="btn btn-default">Change Profile Picture</button>
                    </a>
                </span>
            </div>
            <div class="col-xs-6 col-sm-4 placeholder">
                <div style="height: 150px">
                    <h2><?php echo htmlspecialchars($_SESSION["user"]->getFirstName()) . " " . htmlspecialchars($_SESSION["user"]->getLastName()) ?></h2>
                    <h3 id="personal-message"><?php echo htmlspecialchars($_SESSION["user"]->getPersonalMessage()) ?></h3>
                </div>
                <span class="text-muted">
                    <a class="hover-pointer" data-toggle="modal" data-target="#pm-modal">
                        <button type="button" class="btn btn-default" id="change-pm-modal">Change Personal Message</button>
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
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo htmlspecialchars($_SESSION["user"]->getUsername()) ?></td>
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
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email Address</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo htmlspecialchars($_SESSION["user"]->getFirstName()) ?></td>
                        <td><?php echo htmlspecialchars($_SESSION["user"]->getLastName()) ?></td>
                        <td><?php echo htmlspecialchars($_SESSION["user"]->getEmail()) ?></td>
                    </tr>
                </tbody>
                <thead>
                    <tr>
                        <th colspan="3">
                            Signature
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="1">
                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#signature-modal">
                                Change Signature
                            </button>
                        </td>
                        <td colspan="2" id="signature">
                            <?php echo htmlspecialchars($_SESSION["user"]->getSignature()) ?>
                        </td>
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
                            <input class="btn btn-default btn-primary btn-block" type="submit" value="Change Password">
                        </form>
                    </div>
                    <div id="password-result" class="modal-positive-result"></div>
                    <div id="password-result-negative" class="modal-negative-result"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                        <div id="image-profile-parent">
                            <img src="" id="image-profile" style="display: none; max-width: 500px;"/>
                        </div>
                        <br />
                        <div id="coords" class="hidden"></div>
                        <div id="coords2" class="hidden"></div>
                        <button id="send-coords" style="display: none">Change my Profile Picture!</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Message Modal -->
        <div id="pm-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Change your Personal Message</h4>
                    </div>
                    <div class="modal-body">
                        <form id="changepersonalmessage">
                            <label for="pm" class="sr-only">Personal Message</label>
                            <input name="pm" id="pm" type="text" class="form-control" placeholder="Personal Message" autocomplete="off"
                                   value="<?php echo htmlspecialchars($_SESSION["user"]->getPersonalMessage()) ?>" required autofocus>
                            <br />
                            <input class="btn btn-default btn-primary btn-block" type="submit" value="Update Personal Message">
                        </form>
                    </div>
                    <div id="pm-result" class="result"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Signature Modal -->
        <div id="signature-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Change your Forum Signature</h4>
                    </div>
                    <div class="modal-body">
                        <form id="changesignature">
                            <label for="sig" class="sr-only">Signature</label>
                            <!-- This is all on 1 line to avoid unnecessary indenting when opening the modal -->
                            <textarea name="sig" id="sig" class="form-control" placeholder="Signature" autocomplete="off" required autofocus><?php echo $_SESSION["user"]->getSignature() ?></textarea>
                            <br />
                            <input class="btn btn-default btn-primary btn-block" type="submit" value="Update Signature">
                        </form>
                    </div>
                    <div id="signature-result" class="result"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Context Menus -->

        <!-- Profile Picture Context Menu -->
        <ul class="_contextmenu" id="_contextmenu-profile-picture">
            <li data-action="change-profile-picture">Change Profile Picture</li>
            <li data-action="cropped-profile-picture">View Image</li>
        </ul>

        <?php
    }
    // Load the Dashboard footer
    require_once "pages/templates/footerdashboard.php";
    // Otherwise, the user is not logged in, so send them to the homepage
}else{
    header("Location: index.php");
}