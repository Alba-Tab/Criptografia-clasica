<div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
    <div class="flex border-b">
        <?php 
      $tabs = [
        'displacement'=>'Desplazamiento',
        'transposition'=>'Transposición',
        'substitution'=>'Sustitución',
        'advanced'=>'Avanzados'
      ];
      foreach($tabs as $id=>$label): 
        $active = ($id===($tab??'displacement')) 
                  ? 'border-blue-600 text-blue-600' 
                  : 'border-transparent text-gray-600 hover:border-blue-300 hover:text-blue-600';
    ?>
        <a href="?tab=<?= $id ?>" class="tab-btn px-6 py-3 font-medium border-b-2 <?= $active ?>" data-tab="<?= $id ?>">
            <?= $label ?>
        </a>
        <?php endforeach ?>
    </div>
    <div class="p-6">