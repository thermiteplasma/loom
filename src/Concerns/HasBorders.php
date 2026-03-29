<?php 

namespace Thermiteplasma\Loom\Concerns;

trait HasBorders
{
    public bool|string $border = false;
    public bool|string $borderTop = false;
    public bool|string $borderBottom = false;
    public bool|string $borderLeft = false;
    public bool|string $borderRight = false;
    public string $borderStyle = 'solid';
    public string $borderWidth = '0.5pt';
    public string $borderColor = '#cccccc';
    public ?string $borderRadius = null;

    protected function borderStyles(): array
    {
        $styles = [];

        if ($this->border === true) {
            $styles['border'] = "{$this->borderWidth} {$this->borderStyle} {$this->borderColor}";
        } elseif (is_string($this->border) && $this->border !== '' && $this->border !== '0') {
            $styles['border'] = "{$this->borderWidth} {$this->border} {$this->borderColor}";
        } else {
            foreach (['top', 'bottom', 'left', 'right'] as $side) {
                $prop = 'border' . ucfirst($side);
                $value = $this->{$prop};

                if ($value === true) {
                    $styles["border-{$side}"] = "{$this->borderWidth} {$this->borderStyle} {$this->borderColor}";
                } elseif (is_string($value) && $value !== '' && $value !== '0') {
                    $styles["border-{$side}"] = "{$this->borderWidth} {$value} {$this->borderColor}";
                }
            }
        }

        if ($this->borderRadius) {
            $styles['border-radius'] = $this->borderRadius;
        }

        return $styles;
    }
}