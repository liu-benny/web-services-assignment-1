<?php

class AlbumModel extends BaseModel {

    private $table_name = "album";
    /**
     * A model class for the `album` database table.
     * It exposes operations that can be performed on albums records.
     */
    function __construct() {
        // Call the parent class and initialize the database connection settings.
        parent::__construct();
    }

    /**
     * Retrieve all albums from the `album` table.
     * @return array A list of albums. 
     */
    public function getAll() {
        $sql = "SELECT * FROM album";
        $data = $this->rows($sql);
        return $data;
    }

    /**
     * Get a list of albums whose name matches or contains the provided value.       
     * @param string $albumName 
     * @return array An array containing the matches found.
     */
    public function getWhereLike($albumName) {
        $sql = "SELECT * FROM album WHERE Name LIKE :name";
        $data = $this->run($sql, [":name" => "%" . $albumName . "%"])->fetchAll();
        return $data;
    }

    /**
     * Retrieve an album by its id.
     * @param int $album_id the id of the album.
     * @return array an array containing information about a given album.
     */
    public function getAlbumById($album_id) {
        $sql = "SELECT * FROM album WHERE AlbumId = ?";
        $data = $this->run($sql, [$album_id])->fetch();
        return $data;
    }

    /**
     * Retrieve albums by artist_id.
     * @param int $artist_id the id of the artist.
     * @return array an array containing information about albums.
     */
    public function getAlbumByArtistId($artist_id) {
        $sql = "SELECT * FROM album WHERE ArtistId =?";
        $data = $this->run($sql, [$artist_id])->fetch();
        return $data;
    }

}
