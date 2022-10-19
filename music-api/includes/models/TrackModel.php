<?php

class TrackModel extends BaseModel {

    private $table_name = "track";

    /**
     * A model class for the `track` database table.
     * It exposes operations that can be performed on tracks records.
     */
    function __construct() {
        // Call the parent class and initialize the database connection settings.
        parent::__construct();
    }

    /**
     * Retrieve all tracks from the `track` table.
     * @return array A list of tracks. 
     */
    public function getAll() {
        $sql = "SELECT * FROM track";
        $data = $this->rows($sql);
        return $data;
    }

    /**
     * Get a list of tracks whose name matches or contains the provided value.       
     * @param string $trackName 
     * @return array An array containing the matches found.
     */
    public function getWhereLike($trackName) {
        $sql = "SELECT * FROM track WHERE Name LIKE :name";
        $data = $this->run($sql, [ ":name" => "%" . $trackName . "%"])->fetchAll();
        return $data;
    }

    /**
     * Retrieve an track by its id.
     * @param int $track_id the id of the track.
     * @return array an array containing information about a given track.
     */
    public function getTrackById($track_id) {
        $sql = "SELECT * FROM track WHERE TrackId = ?";
        $data = $this->run($sql, [$track_id])->fetch();
        return $data;
    }

    /**
     * Retrieve an track by artist_id and alubm_id.
     * @param int $artist_id the id of the track.
     * @param int $album_id the id of the album.
     * @return array an array containing information about track.
     */
    public function getTrackByArtistAndAlbumId($artist_id, $album_id) {
        $sql = "SELECT album.ArtistId,album.Title ,track.* 
                FROM track 
                INNER JOIN album ON track.AlbumId = album.AlbumId 
                WHERE album.ArtistId = :artist_id AND album.AlbumId = :album_id ";

        $data = $this->run($sql, [":artist_id" => $artist_id, ":album_id" => $album_id])->fetchAll();
        return $data;
    }

    /**
     * Retrieve purchased tracks by customer_id.
     * @param int $customer_id the id of the track.
     * @return array an array containing information about purchased tracks.
     */
    public function getPurchasedTracksByCustomerId($customer_id) {
        $sql = "SELECT track.* 
                FROM track 
                INNER JOIN invoiceline ON invoiceline.TrackId = track.TrackId 
                INNER JOIN invoice ON invoiceline.InvoiceId = invoice.InvoiceId
                WHERE invoice.CustomerId = :customer_id ";

        $data = $this->run($sql, [":customer_id" => $customer_id])->fetchAll();
        return $data;
    }


}