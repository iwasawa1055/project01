<div class="col-lg-12 text-center">
  <ul class="pagination">
    <?php
    if ($this->Paginator->hasPrev()) {
        echo $this->Paginator->prev('前へ',
          ['tag' => 'li', 'class' => 'paginate_button previous'],
          '<a title="previous">前へ</a>',
          ['tag' => 'li', 'class' => 'paginate_button previous disabled', 'escape' => false]);
    }
    echo $this->Paginator->numbers(
      ['separator' => '', 'tag' => 'li', 'class' => 'paginate_button',
       'currentTag' => 'a', 'currentClass' => 'paginate_button active',
          'modulus' => 4]);

    if ($this->Paginator->hasNext()) {
        echo $this->Paginator->next('次へ',
        ['tag' => 'li', 'class' => 'paginate_button next'],
        '<a title="next">次へ</a>',
        ['tag' => 'li', 'class' => 'paginate_button next disabled', 'escape' => false]);
    }
    ?>
  </ul>
</div>
