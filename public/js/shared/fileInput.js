$('.custom-file-input').on('change', function (event) {
  $(this).next('.form-control-file').addClass("selected").html($(this)[0].files[0].name);

  // Show the name of the selected file in the custom file input
  const file = event?.target?.files?.[0];
  const fileName = file?.name;
  const nextSibling = event?.target?.nextElementSibling;
  if (fileName && nextSibling)
      nextSibling.innerText = fileName;

  const fileReaderSupport = 'FileReader' in window;

  const img = document.getElementById('customFileImage');

  // Preview the image in the page as if it were already uploaded
  if (fileReaderSupport && file && img) {
      const fr = new FileReader();
      fr.onload = () => img.src = fr.result;
      fr.readAsDataURL(file);
  }
});

