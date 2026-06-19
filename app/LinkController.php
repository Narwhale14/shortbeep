<?php

class LinkController {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function shorten(string $url) : string {
        $code = bin2hex(random_bytes(4));
        $statement = $this->db->prepare('INSERT INTO links (code, url) VALUES (?, ?)');
        $statement->execute([$code, $url]);
        return $code;
    }

    public function unwrap(string $code) : ?string {
        $statement = $this->db->prepare('SELECT url FROM links WHERE code = ?');
        $statement->execute([$code]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['url'] : null;
    }
}