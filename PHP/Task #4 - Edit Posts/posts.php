<?php
require_once(BASE_PATH . '/dal/basic_dal.php');

function getPosts(
    $page_size,
    $page = 1,
    $category_id = null,
    $tag_id = null,
    $user_id = null,
    $q = null,
    $order_field = "publish_date",
    $order_by = "desc"
) {

    $offset = ($page - 1) * $page_size;

    $sql = "SELECT p.*,c.name AS category_name,u.name AS user_name FROM posts p
    INNER JOIN categories c ON c.id=p.category_id
    INNER JOIN users u ON u.id=p.user_id
    WHERE 1=1";

    $types = '';
    $vals = [];
    $sql = addWhereConditions($sql, $category_id, $tag_id, $user_id, $q, $types, $vals);
    $sql .= " ORDER BY $order_field $order_by limit $offset,$page_size";

    $posts =  getRows($sql, $types, $vals);
    for ($i = 0; $i < count($posts); $i++) {
        $posts[$i]['comments'] = getPostCommentsCount($posts[$i]['id']);
        $posts[$i]['tags'] = getPostTags($posts[$i]['id']);
    }

    return $posts;
}

function getPostsCount($category_id = null, $tag_id = null, $user_id = null, $q = null)
{
    $sql = "SELECT count(0) as cnt FROM posts p
    INNER JOIN categories c ON c.id=p.category_id
    INNER JOIN users u ON u.id=p.user_id
    WHERE 1=1";
    $types = '';
    $vals = [];
    $sql = addWhereConditions($sql, $category_id, $tag_id, $user_id, $q, $types, $vals);
    return  getRow($sql, $types, $vals)['cnt'];
}

function addWhereConditions($sql, $category_id = null, $tag_id = null, $user_id = null, $q = null, &$types, &$vals)
{
    if ($category_id != null) {
        $types .= 'i';
        array_push($vals, $category_id);
        $sql .= " AND category_id=?";
    }
    if ($user_id != null) {
        $types .= 'i';
        array_push($vals, $user_id);
        $sql .= " AND user_id=?";
    }
    if ($tag_id != null) {
        $types .= 'i';
        array_push($vals, $tag_id);
        $sql .= " AND p.id IN (SELECT post_id FROM post_tags WHERE tag_id=?)";
    }
    if ($q != null) {
        $types .= 'ss';
        array_push($vals, '%' . $q . '%');
        array_push($vals, '%' . $q . '%');
        $sql .= " AND (title like ? OR content like ?)";
    }
    return $sql;
}

function getMyPosts($page_size, $page, $user_id, $q, $order_field, $order_by)
{
    return [
        'data' => getPosts($page_size, $page, null, null, $user_id, $q, $order_field, $order_by),
        'count' => getPostsCount(null, null, $user_id, $q)
    ];
}

function getPostCommentsCount($postId)
{
    $sql = "SELECT COUNT(0) as cnt FROM comments WHERE post_id=$postId";
    $result = getRow($sql);
    if ($result == null) return 0;
    return $result['cnt'];
}


/************************************************************************************* */
function getPostByID($postID)
{
    $ID_QUERY = "SELECT title, content, image, category_id FROM posts WHERE id=?";
    $resultSet = getRows($ID_QUERY, 'i', [$postID]);
    return $resultSet[0];
}

function editPost($submittedData)
{
    $connection = getConnection();
    $UPDATE_QUERY = "UPDATE posts SET title=? , content=? , category_id=? , image=? WHERE id=?";
    [
        "id" => $id,
        "title" => $title,
        "content" => $content,
        "category_id" => $category_id,
        "image" => $image
    ] = $submittedData;

    if ($connection) {
        $statement = mysqli_prepare($connection, $UPDATE_QUERY);

        mysqli_stmt_bind_param($statement, "ssisi", $title, $content, $category_id, $image, $id);
        mysqli_stmt_execute($statement);

        if (mysqli_stmt_error($statement)) {
            var_export(mysqli_stmt_error($statement));
            exit;
            return false;
        }
        return true;
    }
}


function deleteTagsByPostID($postID)
{
    $connection = getConnection();
    $DELETE_POST_ENTRY = "DELETE FROM post_tags WHERE post_id=?";



    if ($connection) {
        $deleteStatement = mysqli_prepare($connection, $DELETE_POST_ENTRY);
        mysqli_stmt_bind_param($deleteStatement, "i", $postID);
        mysqli_stmt_execute($deleteStatement);

        if (mysqli_stmt_error($deleteStatement)) {
            var_export(mysqli_stmt_error($deleteStatement));
            exit;
            return false;
        }

        return true;
    }
}

function insertNewTagsToPost($postID, $tagIDs)
{
    $connection = getConnection();
    $NEW_TAGS_QUERY = "INSERT INTO post_tags(post_id, tag_id) VALUES (?, ?)";

    if ($connection) {
        $insertStatement = mysqli_prepare($connection, $NEW_TAGS_QUERY);

        foreach ($tagIDs as $tagID) {
            $id = intval($tagID);
            mysqli_stmt_bind_param($insertStatement, "ii", $postID, $id);
            mysqli_stmt_execute($insertStatement);
        }

        if (mysqli_stmt_error($insertStatement)) {
            var_export(mysqli_stmt_error($insertStatement));
            return false;
        }

        return true;
    }
}
/************************************************************************************ */
function getPostTags($postId)
{
    $sql = "SELECT t.id,t.name FROM post_tags pt
    INNER JOIN tags t ON t.id=pt.tag_id
    WHERE post_id=$postId";
    return getRows($sql);
}

function validatePostCreate($request)
{
    $errors = [];
    return $errors;
}

function addNewPost($request, $user_id, $image)
{
    $sql = "INSERT INTO posts(id,title,content,image,publish_date,category_id,user_id)
    VALUES (null,?,?,?,?,?,?)";
    $post_id = addData($sql, 'ssssii', [
        $request['title'],
        $request['content'],
        $image,
        $request['publish_date'],
        $request['category_id'],
        $user_id
    ]);
    if ($post_id) {
        if (isset($request['tags'])) {
            foreach ($request['tags'] as $tag_id) {
                addData(
                    "INSERT INTO post_tags (post_id,tag_id) VALUES (?,?)",
                    'ii',
                    [$post_id, $tag_id]
                );
            }
        }
        return true;
    }
    return false;
}

function getUploadedImage($files)
{
    move_uploaded_file($files['image']['tmp_name'], BASE_PATH . '/post_images/' . $files['image']['name']);
    return $files['image']['name'];
}

function updatePost()
{
}
