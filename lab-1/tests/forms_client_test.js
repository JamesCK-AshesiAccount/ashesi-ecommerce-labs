// check the shared form rules without a browser or extra dependencies
const fs = require('fs'), vm = require('vm'), assert = require('assert');
function input(name, value, type = 'text', required = true) {
  return {name, value, type, required, maxLength:100, files:[], error:'', setCustomValidity(message){this.error=message}};
}
function form(inputs) {
  return {inputs,events:{},querySelectorAll(){return this.inputs},appendChild(node){this.message=node},addEventListener(name,fn){this.events[name]=fn},checkValidity(){return this.inputs.every(input=>!input.error)},reportValidity(){this.reported=true}};
}
const name=input('brand_name','Good brand'), brand=form([name]);
const price=input('product_price','49.99','number'), image=input('product_image','','file',false), product=form([price,image]);
const email=input('customer_email','test@example.com','email'), password=input('customer_pass','Secret!12345','password'), login=form([email,password]);
const document={querySelectorAll(){return [brand,product,login]},createElement(){return {setAttribute(){}}},addEventListener(name,fn){fn()}};
vm.runInNewContext(fs.readFileSync('js/forms.js','utf8'),{document,TextEncoder});
function submit(form) { let prevented=false; form.events.submit({preventDefault(){prevented=true}}); return !prevented; }
assert(submit(brand));name.value='   ';assert(!submit(brand));name.value='x'.repeat(101);assert(!submit(brand));name.value='Good brand';assert(submit(brand));
assert(submit(product));price.value='';assert(!submit(product));price.value='-1';assert(!submit(product));price.value='0';assert(submit(product));
image.files=[{type:'text/plain',size:100}];assert(!submit(product));image.files=[{type:'image/jpeg',size:5*1024*1024+1}];assert(!submit(product));image.files=[{type:'image/jpeg',size:100}];assert(submit(product));
assert(submit(login));email.value='invalid';assert(!submit(login));email.value='test@example.com';password.value='';assert(!submit(login));
console.log('PASS: admin names, product price, image type/size and login fields validate on the client');
