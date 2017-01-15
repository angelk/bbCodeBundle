# bbCodeBundle
Symfony bundle for [bb code](https://github.com/angelk/bbCode)

# Installation
```
composer require potaka/bbcode-bundle
```

Add `Potaka\BbcodeBundle\PotakaBbcodeBundle()` in `AppKernel.php`

# Usage
```
$bbCode = $this->get('potaka.bbcode.full');
$html = $getHtml('[b]bold"[/b]'); // <b>bold"</b>
```

> Note that you should pass html escaped string!
> `potaka.bbcode.full` is `FullBbCode` from [bbCode](https://github.com/angelk/bbCode)
