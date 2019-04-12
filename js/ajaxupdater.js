new Ajax.PeriodicalUpdater(
  'getPosts',
  './update.php',
  {
    method: 'get',
    frequency: 5,
  }
)
