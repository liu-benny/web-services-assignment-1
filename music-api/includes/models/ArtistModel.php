<?php

class ArtistModel extends BaseModel {

    private $table_name = "artist";

    /**
     * A model class for the `artist` database table.
     * It exposes operations that can be performed on artists records.
     */
    function __construct() {
        // Call the parent class and initialize the database connection settings.
        parent::__construct();
    }

    /**
     * Retrieve all artists from the `artist` table.
     * @return array A list of artists. 
     */
    public function getAll() {
        $sql = "SELECT * FROM artist";
        $data = $this->rows($sql);
        return $data;
    }

    /**
     * Get a list of artists whose name matches or contains the provided value.       
     * @param string $artistName 
     * @return array An array containing the matches found.
     */
    public function getWhereLike($artistName) {
        $sql = "SELECT * FROM artist WHERE Name LIKE :name";
        $data = $this->run($sql, [ ":name" => "%" . $artistName . "%"])->fetchAll();
        return $data;
    }

    /**
     * Retrieve an artist by its id.
     * @param int $artist_id the id of the artist.
     * @return array an array containing information about a given artist.
     */
    public function getArtistById($artist_id) {
        $sql = "SELECT * FROM artist WHERE ArtistId = ?";
        $data = $this->run($sql, [$artist_id])->fetch();
        return $data;
    }

    public function createArtist($artists){
        $artists = $this->insert("artist",$artists);
        return $artists;
    }

    public function updateArtist($artists,$where){
        $artists = $this->update("artist",$artists,$where);
        return $artists;
    }

    public function deleteArtist($where){
        $artist = $this->delete("artist",$where);
        return $artist;
    }

}
