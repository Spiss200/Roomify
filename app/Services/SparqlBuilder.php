<?php

namespace App\Services;

class SparqlBuilder
{
    protected array $patterns = [];
    protected string $keyword = '';
    protected array $terms = [];
    protected string $sort = 'harga_asc';

    public function keyword(string $q): self
    {
        $this->keyword = trim($q);
        return $this;
    }

    public function terms(array $terms): self
    {
        $this->terms = array_filter(array_map('trim', $terms));
        return $this;
    }

    public function sort(string $sort): self
    {
        $this->sort = $sort;
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

    public function minPrice(int $price): self
    {
        $this->patterns[] = "FILTER(?harga >= $price)";
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

    public function build(int $limit = 200): string
    {
        $extra = implode("\n  ", $this->patterns);

        $kwFilter = '';
        if (!empty($this->terms)) {
            $clauses = [];
            foreach ($this->terms as $t) {
                $safe = addslashes(strtolower($t));
                $clauses[] = "(CONTAINS(LCASE(?nama), '$safe') || CONTAINS(LCASE(?alamat), '$safe') || CONTAINS(LCASE(?desc), '$safe'))";
            }
            $kwFilter = "FILTER(\n    " . implode(" &&\n    ", $clauses) . "\n  )";
        } elseif ($this->keyword !== '') {
            $safe = addslashes(strtolower($this->keyword));
            $kwFilter = "FILTER(
      CONTAINS(LCASE(?nama), '$safe') ||
      CONTAINS(LCASE(?alamat), '$safe') ||
      CONTAINS(LCASE(?desc), '$safe')
    )";
        }

        $orderBy = match ($this->sort) {
            'harga_desc' => 'ORDER BY DESC(?harga)',
            'rating'     => 'ORDER BY DESC(?rating)',
            'nama'       => 'ORDER BY ASC(?nama)',
            default      => 'ORDER BY ASC(?harga)',
        };

        return "PREFIX roomify: <https://roomify.example/ontology#>
SELECT ?id ?nama ?alamat ?harga ?rating ?star ?foto ?lat ?lon WHERE {
  ?h a roomify:Hotel ;
     roomify:name ?nama ;
     roomify:address ?alamat ;
     roomify:startPrice ?harga .
  OPTIONAL { ?h roomify:rating ?rating }
  OPTIONAL { ?h roomify:starRating ?star }
  OPTIONAL { ?h roomify:description ?desc }
  OPTIONAL { ?h roomify:latitude ?lat }
  OPTIONAL { ?h roomify:longitude ?lon }
  OPTIONAL { ?h roomify:hasPhoto ?p . ?p roomify:photoUrl ?foto }
  BIND(STRAFTER(STR(?h), \"#\") AS ?id)
  $extra
  $kwFilter
}
$orderBy
LIMIT $limit";
    }
}
