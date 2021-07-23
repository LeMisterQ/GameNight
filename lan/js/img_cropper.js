//Js de récup des images sans les soumettre au serveur, puis de gestion du cropping

//Ajout des CSS de la page profile_crop
$('head').append('<link rel="stylesheet" href="css/jquery.Jcrop.css" />');
var including_div = $('#img_init');


var max_imgH = screen.height;
var max_imgW = screen.width;


//Fonction de gestion du cropping
$(function () {
  'use strict'

  var result = including_div;
  var exifNode = $('#exif');
  var iptcNode = $('#iptc');
  var thumbNode = $('#thumbnail');
  var actionsNode = $('#actions');
  var currentFile;
  var coordinates;
  var jcropAPI;

  function displayTagData (node, tags) {
    var table = node.find('table').empty()
    var row = $('<tr></tr>')
    var cell = $('<td></td>')
    var prop
    for (prop in tags) {
      if (tags.hasOwnProperty(prop)) {
        table.append(
          row
            .clone()
            .append(cell.clone().text(prop))
            .append(cell.clone().text(tags[prop]))
        )
      }
    }
    node.show()
  }

  function displayThumbnailImage (node, thumbnail, options) {
    if (thumbnail) {
      thumbNode.empty()
      loadImage(
        thumbnail,
        function (img) {
          node.append(img).show()
        },
        options
      )
    }
  }

  function displayMetaData (data) {
    if (!data) return
    var exif = data.exif
    var iptc = data.iptc
    if (exif) {
      displayThumbnailImage(thumbNode, exif.get('Thumbnail'), {
        orientation: exif.get('Orientation')
      })
      displayTagData(exifNode, exif.getAll())
    }
    if (iptc) {
      displayTagData(iptcNode, iptc.getAll())
    }
  }

  function updateResults (img, data) {
    var fileName = currentFile.name
    var href = img.src
    var dataURLStart
    var content
    if (!(img.src || img instanceof HTMLCanvasElement)) {
      content = $('<span>Loading image file failed</span>')
    } else {
      if (!href) {
        href = img.toDataURL(currentFile.type + 'REMOVEME')
        // Check if file type is supported for the dataURL export:
        dataURLStart = 'data:' + currentFile.type
        if (href.slice(0, dataURLStart.length) !== dataURLStart) {
          fileName = fileName.replace(/\.\w+$/, '.png')
        }
      }
      content = $('<a target="_blank">')
        .append(img)
        .attr('download', fileName)
        .attr('href', href)
    }
    result.children().replaceWith(content)
    if (img.getContext) {
      actionsNode.show()
    }
    displayMetaData(data)
  }

  function displayImage (file, options) {
    currentFile = file
    if (!loadImage(file, updateResults, options)) {
      result
        .children()
        .replaceWith(
          $(
            '<span>' +
              'Your browser does not support the URL or FileReader API.' +
              '</span>'
          )
        )
    }
  }

  function dropChangeHandler (e) {
    e.preventDefault()
    e = e.originalEvent
	
	//On affiche les divs qui nous intéressent (Edit, Crop)
	$("div[id='img_cropper']").animate({opacity: 1}, 500, function() {
		// Animation complete.
	});
	
    var target = e.dataTransfer || e.target
    var file = target && target.files && target.files[0]
    var options = {
      maxWidth: max_imgW,
	  maxHeight: max_imgH,
      canvas: true,
      pixelRatio: window.devicePixelRatio,
      downsamplingRatio: 0.5,
      orientation: true
    }
    if (!file) {
      return
    }
    exifNode.hide()
    iptcNode.hide()
    thumbNode.hide()
    displayImage(file, options)
  }

  // Hide URL/FileReader API requirement message in capable browsers:
  if (
    window.createObjectURL ||
    window.URL ||
    window.webkitURL ||
    window.FileReader
  ) {
    result.children().hide()
  }

  $(document)
    .on('dragover', function (e) {
      e.preventDefault()
      e = e.originalEvent
      e.dataTransfer.dropEffect = 'copy'
    })
    .on('drop', dropChangeHandler)

  $('#up_avatar_file').on('change', dropChangeHandler)

  $('#edit').on('click', function (event) {
    event.preventDefault()
    var imgNode = result.find('img, canvas')
    var img = imgNode[0];
    var pixelRatio = window.devicePixelRatio || 1
	var img_h_w_ratio = img.width/img.height;
	//console.log("img_h_w_ratio " +img_h_w_ratio+ " img.width " + img.width + " img.height " + img.height + " img.width / pixelRatio " + img.width / pixelRatio + " img.height / pixelRatio " + img.height / pixelRatio);
    
	imgNode
      .Jcrop(
        {		  		  
          setSelect: [
		  
            (img.width / pixelRatio)*(1/5),
			(img.height / pixelRatio)*(1/5)/img_h_w_ratio,
            (img.width / pixelRatio)*(4/5),
			(img.height / pixelRatio)*(4/5)/img_h_w_ratio

			/*Q code
			(img.width / pixelRatio)/3,
            img.height - (img.width / pixelRatio)/3/2,
            (img.width / pixelRatio) - (img.width / pixelRatio)/3,
            img.height - (img.height - (img.width / pixelRatio)/3/2)
			*/
			
			/* Original code
            img.width / pixelRatio - 100,
            img.height / pixelRatio - 100
			*/	
			
          ],
          onSelect: function (coords) {
            coordinates = coords
          },
          onRelease: function () {
            coordinates = null
          }
        },
        function () {
          jcropAPI = this
        }
      )
      .parent()
      .on('click', function (event) {
        event.preventDefault()
      })
  })

  $('#crop').on('click', function (event) {
    event.preventDefault()
    var img = result.find('img, canvas')[0]
    var pixelRatio = window.devicePixelRatio || 1
    if (img && coordinates) {
      updateResults(
        loadImage.scale(img, {
          left: coordinates.x * pixelRatio,
          top: coordinates.y * pixelRatio,
          sourceWidth: coordinates.w * pixelRatio,
          sourceHeight: coordinates.h * pixelRatio,
          minWidth: result.width(),
          maxWidth: result.width(),
          pixelRatio: pixelRatio,
          downsamplingRatio: 0.5
        })
      )
      coordinates = null
    }
  })

  $('#cancel').on('click', function (event) {
    event.preventDefault()
    if (jcropAPI) {
      jcropAPI.release()
    }
  })
})