<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <x-filament::icon-button
        icon="heroicon-o-trash"
        color="warning"
        wire:click="clear"
        wire:loading.attr="disabled"
        tooltip="Hapus Cache Aplikasi"
    />
</div>