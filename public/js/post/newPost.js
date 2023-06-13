document.addEventListener('DOMContentLoaded', function (event) {
  $('#category-search_multi')
    .select2({
      placeholder: $(this).data('placeholder'),
      closeOnSelect: false,
    })

  $('#category-search_multi').on(
    'select2:opening select2:closing',
    function () {
      let $searchfield = $(this).parent().find('.select2-search__field');
      $searchfield.prop('disabled', true);
    }
  );
});