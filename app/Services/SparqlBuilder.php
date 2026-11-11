<?php

namespace App\Services;

class SparqlBuilder
{
    protected array $patterns = [];
    protected string $keyword = '';

    public function keyword(string $q): self
    {
        $this->keyword = trim($q);
        return $this;
    }

    public function facility(string $fac): self
    {
        $this->patterns[] = "?h roomify:hasFacility roomify:$fac .";
        return $this;
    }

    public function maxPrice(int $price): self
    {
        $this->patterns[] = "FILTER(?harga <= $price)";
        return $this;
    }

    public function minRating(float $r): self
    {
        $this->patterns[] = "FILTER(?rating >= " . number_format($r, 1, '.', '') . ")";
        return $this;
    }

    public function minStar(int $s): self
    {
        $this->patterns[] = "FILTER(?star >= $s)";
        return $this;
    }

    public function landmark(string $name): self
    {
        $safe = addslashes($name);
        $this->patterns[] = "?h roomify:nearLandmark ?lmf . ?lmf roomify:name \"$safe\" .";
        return $this;
    }

    public function build(int $limit = 30): string
    {
        $extra = implode("\n  ", $this->patterns);

        $kwFilter = '';
        if ($this->keyword !== '') {
            $safe = addslashes(strtolower($this->keyword));
            $kwFilter = "FILTER(
      CONTAINS(LCASE(?nama), '$safe') ||
      CONTAINS(LCASE(?alamat), '$safe') ||
      CONTAINS(LCASE(?desc), '$safe')
    )";
        }

        return "PREFIX roomify: <https://roomify.example/ontology#>
SELECT ?id ?nama ?alamat ?harga ?rating ?star ?foto WHERE {
  ?h a roomify:Hotel ;
     roomify:name ?nama ;
     roomify:address ?alamat ;
     roomify:startPrice ?harga .
  OPTIONAL { ?h roomify:rating ?rating }
  OPTIONAL { ?h roomify:starRating ?star }
  OPTIONAL { ?h roomify:description ?desc }
  OPTIONAL { ?h roomify:hasPhoto ?p . ?p roomify:photoUrl ?foto }
  BIND(STRAFTER(STR(?h), \"#\") AS ?id)
  $extra
  $kwFilter
}
ORDER BY ASC(?harga)
LIMIT $limit";
    }
}
