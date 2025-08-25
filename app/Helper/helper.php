<?php
use Illuminate\Support\Facades\Config;

function settings($key = null, array $replace = [])
{
  if ($key === null) {
    return collect(config('settings'));
}

$settings = collect(config('settings'))->mapWithKeys(function ($a) {
    return $a;
});

$value = isset($settings[$key]) ? (is_array($settings[$key]['value']) ? $settings[$key]['value'][app()->getLocale()] : $settings[$key]['value']) : null;
if (!empty($replace)) {
      $value = replace($replace, $value);
    } else {
    return $value;
    }

}
function arrayToPhpArray($arr)
{
  return "<?php \n\nreturn ".arrayToString($arr).';';
}
function arrayToString($arr, $tabs = 0, $startTabs = true)
{
  return rtrim(arrayToStringWrapper($arr, $tabs, $startTabs), ",\n ");
}

function arrayToStringWrapper($arr, $tabs = 0, $startTabs = true)
{
  $result = ($startTabs ? str_repeat("\t", $tabs) : '').'['."\n";
  if ($startTabs) {
    $tabs++;
  }
  foreach ($arr as $key => $value) {
    $result .= str_repeat("\t", $tabs).json_encode($key, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).' => ';
    if (is_array($value)) {
      $result .= arrayToStringWrapper($value, $tabs + 1, false);
    } else {
      $result .= json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).",\n";
    }
  }
  $tabs--;
  $result .= str_repeat("\t", $tabs).'],'."\n";

  return $result;
}

function settingTransAttrs($setting)
{
  return collect($setting)->filter(
    function ($item) {
      return is_array($item['value']);
    }
  );
}
function settingNonTransAttrs($setting)
{
  return collect($setting)->filter(
    function ($item) {
      return ! is_array($item['value']);
    }
  );
}
function sectionTypes()
{

  return collect(Config::get('pageTypes'))->sortBy(function ($value, $key) {
    return $value['id'];
  });
}
function TypeFolder()
{

  return collect(Config::get('pageTypes'))->sortBy(function ($value, $key) {
    return $value;
  });
}
function locales()
{
    $locales = [];
    
    foreach (Config::get('app.locales') as $locale) {
        $currentLocale = app()->getLocale();
        $thisLocale = $locale;
        $locales[$locale] = str_replace($currentLocale, $thisLocale, url()->full());
    }

    return $locales;

}