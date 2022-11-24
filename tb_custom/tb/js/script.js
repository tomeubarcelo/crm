console.log("init")

function delete_image(name) {
    $("[name='uploadedFileSize_" + name + "']").html('')
    $("[name='uploadedFileName_" + name + "']").html('')
}