<?php

class P
{
    private static $init = null;
    private $getParams = [];
    private $content = null;
    private $uniqueTags = [];

    public static function init()
    {
        if (!self::$init) {
            self::$init = new self();
        }
        return self::$init;
    }

    public function hasUrl(): bool
    {
        return $this->isPost() && count($this->getParams) && !empty($this->getParams['url']);
    }

    public function getParams(): array
    {
        return $this->getParams;
    }

    public function setParams(array $params): bool
    {
        if ($this->getParams = $params) {
            return true;
        }

        return false;
    }

    public function setParamsFromRequest(): bool
    {
        return $this->setParams($_POST);
    }

    public function isPost(): bool
    {
        return (boolean)$_POST;
    }

    public function setContent(): bool
    {
        return ($this->content = file_get_contents($this->getParams()['url']));
    }

    public function getUniqueTags()
    {
        return $this->uniqueTags;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getTagsByPreg()
    {
        $this->setContent();
        if (preg_match_all("/<\/[a-z]+>/", $this->content, $matches)) {
            return $matches;
        }
    }

    public function run()
    {
        $matches = $this->getTagsByPreg();

        foreach ($matches[0] as $tag) {
            $this->uniqueTags[trim($tag, '<>/')]++;
        }
    }

}

$parser = P::init();
$parser->setParamsFromRequest();


if ($parser->isPost()) {
    $parser->run();

    var_dump($parser->getUniqueTags());
}

?>

<form action="/parser.php" method="POST">
    <input type="text" name="url" value="<?= ($parser->isPost() && $parser->hasUrl()) ? $parser->getParams()['url'] : null ?>"/>
    <input type="submit"/>
</form>
