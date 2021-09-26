<?php

class Comment implements JsonSerializable
{
    private int $id;
    private int $userID;
    private string $authorName;
    private int $postID;
    private string $text;
    private string $date;
    private string $likesCount;

    /**
     * @param int $id
     * @param int $userID
     * @param int $postID
     * @param string $text
     * @param string $date
     */
    public function __construct(int $id, int $userID, int $postID, string $text, string $date)
    {
        $this->id = $id;
        $this->userID = $userID;
        $this->postID = $postID;
        $this->text = $text;
        $this->date = $date;
    }

    public function getLikesCount(){
        return $this->likesCount;
    }

    public function setLikesCount($likesCount){
        $this->likesCount = $likesCount;
    }

    public function setAuthorName($authorName){
        $this->authorName = $authorName;
    }

    public function getAuthorName(){
        return $this->authorName;
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getUserID(): int
    {
        return $this->userID;
    }

    /**
     * @param int $userID
     */
    public function setUserID(int $userID): void
    {
        $this->userID = $userID;
    }

    /**
     * @return int
     */
    public function getPostID(): int
    {
        return $this->postID;
    }



    /**
     * @param int $postID
     */
    public function setPostID(int $postID): void
    {
        $this->postID = $postID;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function jsonSerialize() {
        return [
                'id'   => $this->id,
                'userID' => $this->userID,
                'postID' => $this->postID,
                'authorName' => $this->authorName,
                'content' => $this->text,
                'date' => $this->date,
                'likesCount' =>$this->likesCount
            ];
    }
}