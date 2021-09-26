<?php
require_once(BASE_PATH.'/utils/database/Database.php');
require_once(BASE_PATH.'/controllers/UserController.php');


$database = Database::getInstance();
$connection = $database->getConnection();

$userController = new UserController($connection);
$users = $userController->findAllUsers();

$output = '';

for($i = 0; $i < count($users); $i++) 
{
    $active = $users[$i]["active"] ? "Active" : "Inactive";
    $type = $users[$i]["type"] ? "Admin" : "Author";
    $encoded = $users[$i]['active'] ? 0 : 1;

    $output.=
    '<tr data-id="'.$users[$i]['id'].'" data-active="'.$encoded.'">'.'
        <th>'. $i+1 .'</th>
        <td>'.$users[$i]["name"].'</td>
        <td>'.$users[$i]["email"].'</td>
        <td>'.$active.'</td>
        <td>'.$type.'</td>
        <td style="display:flex;">
            <form class="restrict-user" method="GET">';
            if($active === "Active") {
                $output.='<button type="submit" name="" class="btn btn-primary" style="margin-right: 8px;"><i class="bi bi-lock-fill"></i></button>';

            }else{
                $output.='<button type="submit" class="btn btn-primary" style="margin-right: 8px;"><i class="bi bi-unlock-fill"></i></button>';
            }

            $output.=
            '
            </form>
            <form class="remove-user" method="GET">
                <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>
            </form>
        </td>
    </tr>';
}

echo $output;