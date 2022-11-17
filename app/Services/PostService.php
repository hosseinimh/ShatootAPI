<?php

require_once(__DIR__ . '/../Models/Post.php');

class PostService
{
    public function insert($id, $name, $userId)
    {
        $post = new Post();

        $row = $id > 0 ? $post->get($id) : null;

        if ($row) {
            $post->updateRow($id, $name);
        } else {
            $id = $post->createRow($userId, $name);
        }

        return $post->get($id);
    }
}
