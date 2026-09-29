class MyFileReader{

	constructor(param={container:container,element:element,removeBtnClassName:removeBtnClassName,dargAndDropTitleDragged:dargAndDropTitleDragged,dargAndDropTitleMain:dargAndDropTitleMain,removeBtnText:removeBtnText}){
		this.element = param.element;
		this.dropArea = param.container;
		this.dragAndDropBox = this.element.querySelector(".drag-and-drop-box");
		this.dragAndDropTitle = this.element.querySelector(".drag-and-drop-title");
		// console.log(element.querySelector(".drag-and-drop-title"))
		this.files = [];
		this.removeBtnClassName = param.removeBtnClassName;
		this.dargAndDropTitleDragged = param.dargAndDropTitleDragged;
		this.dargAndDropTitleMain = param.dargAndDropTitleMain;
		this.removeBtnText = param.removeBtnText;
		
		this.dropArea.addEventListener("dragover",function(event){
			event.preventDefault();

			this.dragAndDropTitle.style.transition = "1s";
			this.dragAndDropTitle.textContent = this.dargAndDropTitleDragged;
			// console.log("dragged");
		}.bind(this));

		this.dropArea.addEventListener("dragleave",function(event){
			event.preventDefault();

			this.dragAndDropTitle.style.transition = "1s";
			this.dragAndDropTitle.textContent = this.dargAndDropTitleMain;

			// console.log("dragged");
		}.bind(this));
		// console.log(this.dragAndDropBox);
		this.dragAndDropBox.addEventListener("mouseover",function(event){
			
			this.dragAndDropTitle.style.transition = "1s";
			this.dragAndDropTitle.style.fontSize = "24px";

		}.bind(this));

		this.dragAndDropBox.addEventListener("mouseleave",function(event){
			
			this.dragAndDropTitle.style.transition = "1s";
			this.dragAndDropTitle.style.fontSize = "18px";

		}.bind(this));

		this.dragAndDropTitle.addEventListener('click',function(event){

			this.dropArea.querySelector("input[type='file']").click()
		}.bind(this))

		this.dropArea.addEventListener("drop", function(event){
	      	event.preventDefault(); //preventing from default behaviour
	      	
	      	// file = event.dataTransfer.files[0];
	      	let file = event.dataTransfer.files[0];
	      	
	      	this.files.push(event.dataTransfer.files[0]);
	      	this.showFile(file);
	      	// console.log(this.files);
	      	this.dragAndDropTitle.textContent = this.dargAndDropTitleMain;
	    }.bind(this));
	}

	showFile(file){
		
		let fileType = file.type;
		let validExtensions = ["image/jpeg", "image/jpg", "image/png"]; 

		if(validExtensions.includes(fileType)){ 
	        let fileReader = new FileReader(); 
			fileReader.onload = ()=>{
				let fileURL = fileReader.result;
				let image = 
					'<div class="dropped-image-1" style="width: 200px;border: 0px dashed lightgrey;border-radius: 0px;margin:5px;"><img src=' + fileURL +' style="width:100%"><button class="'+this.removeBtnClassName+'" style="padding:0;border:none;outline:none;">'+this.removeBtnText+'</button></div>'; 
				let droppedImage = this.dropArea.querySelectorAll(".dropped-image-1");
				// console.log(droppedImage);
				droppedImage[droppedImage.length-1].after(this.toNodes(image));
				// console.log(this.removeBtnClassName);
				let removeBtns = this.element.querySelectorAll("."+this.removeBtnClassName);
				// console.log(removeBtns);
	       		// console.log(removeBtns);
	       		
	       		let ri = removeBtns.length-1
	       		removeBtns[removeBtns.length-1].addEventListener('click',function(event){
	       			event.preventDefault();
	       			// console.log(removeBtns.length-1);
	       			// console.log(event.target.parentElement);
	       			this.files.splice(ri,1);
	       			event.target.parentElement.parentElement.remove();
	       		}.bind(this));

	       		if(this.fileReaderAfterLoad){
	       			this.fileReaderAfterLoad();
	       		}
			}	
       		fileReader.readAsDataURL(file);

       		
		}else{
			alert("This is not an Image File!");
		// dropArea.classList.remove("active");
		// dragText.textContent = "Drag & Drop to Upload File";
		}
	}

	toNodes(html){
		return new DOMParser().parseFromString(html, 'text/html').body.childNodes[0];
	}

	getFiles(){
		return this.files;
	}

	getFileDropAreaNode(){

		return this.dropArea;
	}
	fileReaderAfterLoad(){}
}

class MyFileReaderForSingleContainer{

	constructor(classname,container,input){

		this.preview = document.querySelector("."+container+"."+classname+" img");

		input.oninput = function(event){
			// console.log(event);
			let file = event.target.files[0];
			// console.log(file);			
			let fileType = file.type;
			let validExtensions = ["image/jpeg", "image/jpg", "image/png"]; 

			if(validExtensions.includes(fileType)){ 
				let fileReader = new FileReader(); 
				fileReader.onload = ()=>{
					let fileURL = fileReader.result;
					
					this.preview.src = fileURL;

					// fileReaderAfterLoad();
				}	
				fileReader.readAsDataURL(file);

				
			}else{
				alert("This is not an Image File!");
			// dropArea.classList.remove("active");
			// dragText.textContent = "Drag & Drop to Upload File";
			}
		}.bind(this);

	}
}

// console.log("Hi");