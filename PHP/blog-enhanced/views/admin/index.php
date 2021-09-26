<?php
require_once('../../path.config.php');
require_once(BASE_PATH.'/utils/auth/session.php');

$userID = getUserId();

if(!isAdmin()) {
    header('Location: '.BASE_URL);
    die();
}

?>
<?php require_once(BASE_PATH.'/partials/header.php') ?>
<section>
<div class="container">
    <div class="row">
        <div class="col-12">
        <table class="table" style="margin-top: 150px;">
            <thead class="table-dark">
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Type</th>
                <th scope="col">Active</th>
                <th scope="col">Actions</th>
            </thead>
            <tbody id="users">
                <?php require_once('users.php') ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
</section>

<script>
    $(document).ready(function(){
        $('.restrict-user').on('submit', function(event){
            event.preventDefault();

            const activity = Number(event.currentTarget.parentElement.parentElement.dataset.active);
            const userID = Number(event.currentTarget.parentElement.parentElement.dataset.id);
            
            console.log(activity, userID);

            $.ajax({
                url:`restrict-user.php?id=${userID}&activity=${activity}`,
                method: 'GET',
                success: function(response) {
                    $("#users").html(response);
                },
                error: function(xhr,rror){
                    console.log(xhr);
                }
            });
        });

        $('.remove-user').on('submit', function(event){
            event.preventDefault();
            const userID = Number(event.currentTarget.parentElement.parentElement.dataset.id);
            
            $.ajax({
                url:`remove-user.php?id=${userID}`,
                method: 'GET',
                success: function(response){
                    console.log(response);
                    $("#users").html(response);
                },
                error: function(xhr,rror){
                    console.log(error);
                }
            });
        })
    });
</script>

<?php require_once(BASE_PATH.'/partials/footer.php') ?>