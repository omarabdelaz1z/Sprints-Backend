<?php

class Post {
    private int $id;
    private int $likesCount;
    private int $commentCount;
    private int $userID;
    private string $authorName;
    private Category $category;
    private array $comments = [];
    private array $tags = [];
    private string $title;
    private string $content;
    private string $image;
    private string $createdAtDate;
    private string $publishDate;
    private string $updatedAtDate;


    /**
     * @param int $userID
     * @param string $title
     * @param string $content
     * @param string $image
     * @param string $publishDate
     */
    public function __construct(int $id, int $userID, string $authorName, string $title, string $content, string $image, string $publishDate)
    {
        $this->id = $id;
        $this->userID = $userID;
        $this->authorName = $authorName;
        $this->title = $title;
        $this->content = $content;
        $this->image = $image;
        $this->publishDate = $publishDate;
    }

    public function setLikesCount($likesCount){
        $this->likesCount = $likesCount;
    }

    public function getLikesCount(){
        return $this->likesCount;
    }
    
    public function setCommentCount(int $commentCount){
        $this->commentCount = $commentCount;
    }

    public function getCommentCount(){
        return $this->commentCount;
    }
    
    /**
     * @return int
     */
    public function getUserID(): int
    {
        return $this->userID;
    }

    public function getID(){
        return $this->id;
    }

    public function getAuthorName(){
        return $this->authorName;
    }



    /**
     * @param int $userID
     */
    public function setUserID(int $userID): void
    {
        $this->userID = $userID;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getCreatedAtDate(): string
    {
        return $this->createdAtDate;
    }

    /**
     * @param string $createdAtDate
     */
    public function setCreatedAtDate(string $createdAtDate): void
    {
        $this->createdAtDate = $createdAtDate;
    }

    /**
     * @return string
     */
    public function getPublishDate(): string
    {
        return $this->publishDate;
    }

    /**
     * @param string $publishDate
     */
    public function setPublishDate(string $publishDate): void
    {
        $this->publishDate = $publishDate;
    }

    /**
     * @return string
     */
    public function getUpdatedAtDate(): string
    {
        return $this->updatedAtDate;
    }

    /**
     * @param string $updatedAtDate
     */
    public function setUpdatedAtDate(string $updatedAtDate): void
    {
        $this->updatedAtDate = $updatedAtDate;
    }

    /**
     * @param array $comments
     */
    public function setComments(array $comments): void
    {
        $this->comments = $comments;
    }

    /**
     * @return array
     */
    public function getComments(): array
    {
        return $this->comments;
    }

}