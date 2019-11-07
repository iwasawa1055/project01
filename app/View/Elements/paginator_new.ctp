<ul class="ls-pager">
  <?php
    if ($this->Paginator->hasPrev()) {
      echo $this->Paginator->prev(
        '前へ',
        [
          'tag'   => 'li',
          'class' => 'l-pager l-prev',
        ],
        '<a title="previous">前へ</a>',
        [
          'tag'    => 'li',
          'class'  => 'l-pager l-prev disabled',
          'escape' => false,
        ]
      );
    }
    echo $this->Paginator->numbers(
      [
        'separator'    => '',
        'tag'          => 'li',
        'class'        => 'l-pager',
        'currentTag'   => 'a',
        'currentClass' => 'active',
        'modulus'      => 4
      ]
    );

    if ($this->Paginator->hasNext()) {
      echo $this->Paginator->next(
        '次へ',
        [
          'tag'   => 'li',
          'class' => 'l-pager l-next',
        ],
        '<a title="next">次へ</a>',
        [
          'tag'    => 'li',
          'class'  => 'l-pager l-next disabled',
          'escape' => false,
        ]
      );
    }
  ?>
</ul>