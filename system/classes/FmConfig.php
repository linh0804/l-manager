<?php
defined('ACCESS') or exit;
class FmConfig
{
    private array $configs = [];
    private string $config_file;
    private string $prefix;

    public function __construct(string $config_file)
    {
        $this->config_file = $config_file;
        $this->prefix = "<?php defined('ACCESS') or exit; ?>" . PHP_EOL;
        $this->init();
    }

    public function init(): void
    {
        $tmp = dirname($this->config_file) . '/_env.tmp.php';
        if (is_file($tmp)) {
            @unlink($tmp);
        }

        $content = (string) @file_get_contents($this->config_file);

        if (strncmp($content, $this->prefix, strlen($this->prefix)) !== 0) {
            $this->configs = [];
            return;
        }

        $json = substr($content, strlen($this->prefix));
        $data = json_decode($json, true);
        $this->configs = is_array($data) ? $data : [];
    }

    public function save(): void
    {
        $content = $this->prefix . json_encode($this->configs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $tmp = dirname($this->config_file) . '/_env.tmp.php';
        file_put_contents($tmp, $content, LOCK_EX);
        rename($tmp, $this->config_file);
    }

    public function get(string $key, $default = null)
    {
        return $this->configs[$key] ?? $default;
    }

    public function set(array $data): void
    {
        $this->configs = array_merge($this->configs, $data);
        $this->save();
    }

    public function unset(string $key): void
    {
        unset($this->configs[$key]);
        $this->save();
    }
}
