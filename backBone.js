export class Registration {
    constructor(regID, name1, name2, phoneNo, email, password, gender, location,accStatus) {
        this.regID = regID;
        this.name1 = name1;
        this.name2 = name2;
        this.phoneNo = phoneNo;
        this.email = email;
        this.password = password;
        this.gender = gender;
        this.regType = regType;
        this.location = location;
        this.accStatus = accStatus;
    }

    getReg() {
        return {
            regID: this.regID,
            name1: this.name1,
            name2: this.name2,
            phoneNo: this.phoneNo,
            email: this.email,
            password: this.password,
            gender: this.gender,
            regType:this.regType,
            location:this.location,
            accStatus: this.accStatus
        };
    }
}

export class Manager extends Registration {
    constructor(regID, name1, name2, phoneNo, email, password, gender, location, accStatus, managerID) {
        super(regID, name1, name2, phoneNo, email, password, gender, location, accStatus);
        this.managerID = managerID;
    }

    getManager() {
        return {
            regID: this.regID,
            name1: this.name1,
            name2: this.name2,
            phoneNo: this.phoneNo,
            email: this.email,
            password: this.password,
            gender: this.gender,
            regType: this.regType,
            location: this.location,
            accStatus: this.accStatus,
            managerID: this.managerID
        };
    }

    async approveReg(managerID){}
    async rollCall(){}
    async disbursePay(){}
    async viewStock(){}
    async viewBalance(){}
    async disableDormant(){}
    async getFeedback(){}
}

export class Customer extends Registration {
    constructor(regID, name1, name2, phoneNo, email, password, gender, location, accStatus, customerID) {
        super(regID, name1, name2, phoneNo, email, password, gender, location, accStatus);
        this.customerID = customerID;
    }
    newCustomer(){}
    getCustomer() {
        return {
            regID: this.regID,
            name1: this.name1,
            name2: this.name2,
            phoneNo: this.phoneNo,
            email: this.email,
            password: this.password,
            gender: this.gender,
            regType: this.regType,
            location: this.location,
            accStatus: this.accStatus,
            customerID: this.customerID
        };
    }
    
    async Order(){

        async function getMenu(unitID) {
            const menu = await postFoodItems();
            if(menu){
                return menu;
            } else {}
        }

        async function addItem(customerID, unitID){
            // Add the item to the customer's cart
            const cart = [];
            const newItem = () =>{};
            if(newItem){
                cart.push(newItem);
                return cart;
            } else {}
        }
        async function removeItem(){}
        async function viewCart(){}
        async function placeOrder(){}
        async function viewOrderHistory(){}
        async function viewOrderDetails(){}
    }
    async Funds(){
        async function deposit(customerID){}
        async function balance(customerID){}
        async function pay(customerID){}
        async function transactionHistory(customerID   ){}
        async function discount(){}
    }
    async Review(){
        async function writeReview(){}
        async function viewAllReviews(){}
        async function viewMyReviews(){}
        async function rateProduct(){}
        async function viewProductRatings(){}
        async function viewProductDetails(){}
    }
}

export class Chef extends Registration {
    constructor(regID, name1, name2, phoneNo, email, password, gender, location, accStatus, chefID) {
        super(regID, name1, name2, phoneNo, email, password, gender, location, accStatus);
        this.chefID = chefID;
    }
    async createChef(){}
    async getChef() {
        return {
            regID: this.regID,
            name1: this.name1,
            name2: this.name2,
            phoneNo: this.phoneNo,
            email: this.email,
            password: this.password,
            gender: this.gender,
            regType: this.regType,
            location: this.location,
            accStatus: this.accStatus,
            chefID: this.chefID
        };
    }
    async Food(){
        async function foodAvailable(unitID){
            const foodItems = [];
            return foodItems;
        }
        async function addFoodItem(unitID){
            const foodItems = await foodAvailable();
            const newFood = () =>{};

            if(newFood){
                foodItems.push(newFood);
                return foodItems;
            } else {
                return foodItems;
            }
        }
        async function viewFoodItems(unitID){
            const foodItems = await addFoodItem();
            return foodItems;
        }
        async function editFoodItem(unitID, chefID){
            const foodItems = await viewFoodItems();
            const updatedFood = () =>{};

            if(updatedFood){
                foodItems.forEach((item, index) => {
                    if(item.unitID === unitID){
                        foodItems[index] = updatedFood;
                    }
                });
                return foodItems;
            } else {
                return foodItems;
            }
        }
        async function  postFoodItems(unitID){
            const foodItems = await editFoodItem();
            foodItems.forEach((item, index) => {});
        }
        async function removeFoodItem(unitID, chefID){
            const foodItems = await editFoodItem();
        }
    }
    async Menu(){
        async function addMenuItem(unitID, chefID){
            const menuItems = await postFoodItems();
            const newMenuItem = () =>{};
            if(newMenuItem){
                menuItemsItems.push(newMenuItem);
                return menuItems;
            } else {
                return menuItems;
            }
        }
        async function viewMenu(unitID){
            const menuItems = await addMenuItem();
            return menuItems;
        }
        async function editMenuItem(unitID, chefID){
            const menuItems = await viewMenu();
            const updatedMenuItem = () =>{};

            if(updatedMenuItem){
                menuItems.forEach((item, index) => {
                    if(item.unitID === unitID){
                        menuItems[index] = updatedMenuItem;
                    }
                });
                return menuItems;
            } else {
                return menuItems;
            }
        }
        async function removeMenu(){}
    }
    
    async Orders(){
        async function viewAllOrders(unitID){
            const order = await addItem(customerID);
            order.forEach(() =>{
                const orders = () =>{};
                return orders;
            });

        }
        async function viewPendingOrders(unitID){
            const orders = await viewAllOrders(unitID);
            orders.forEach((item, index) =>{
            if(item.served === "NO"){
                const pendingOrders = [];
                pendingOrders.push(item);
            } else {}
            });
            return pendingOrders;
        }
        async function viewCompletedOrders(unitID){}
        async function viewCancelledOrders(unitID, customerID){}
        async function viewOrderDetails(orderID, customerID){}
        async function acceptOrder(){}
        async function rejectOrder(){}
    }
    
}