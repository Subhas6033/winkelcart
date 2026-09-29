
class Component{

	constructor(root,componentHtml,childElement="div"){

	  if(!root){

	  	console.error("Invalid parent node");
	  	return;
	  }

	  this.root = root;
	  
	  if(!componentHtml){

		console.error("Component view (HTML) is not defined!");
		return;
	  } 

		this.html = componentHtml;
		this.counter = 1;
		this.childElement = childElement;

		this.childClass = undefined;
	}

	addClassToChild(classname){

		this.childClass = classname;

		console.log(this.root.children);
	}

	add(){

		if(this.beforeAdd) this.beforeAdd();

		let element = document.createElement(this.childElement);
		element.innerHTML = this.html();

		if(this.childClass) element.classList.add(this.childClass);

		this.addEventListenerForThis(element);

		this.root.append(element);
		
		if(this.afterAdd) this.afterAdd();
		
		this.counter++;
	}
	removeParent(){
		this.root.remove();
	}
	addEventListenerForThis(element){}
	afterAdd(){}
	beforeAdd(){}
}

class ItemList{

	constructor(){

		this.items = [];
		this.isEmpty = true;
	}
	addItem(item){
		this.items.push(item);	
		this.isEmpty = false;
	}
	removeItem(index=undefined){

		if(index){
			this.items.splice(index,1);
		}
		else{
			this.items.pop();
		
		}
		if(this.items.length < 1) this.isEmpty = true;
	}
}