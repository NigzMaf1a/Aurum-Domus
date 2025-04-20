export default class Order{
    constructor(customerID){
        this.customerID = customerID;
        this.orderUrl = "/Scriptz/Backend/orders.php";
    }
    async addOrder(unitID, customerID, dishID, dishName, dishPrice, plates, orderPrice, orderDate, orderTime, paymentStatus = 'Not Paid', served = 'NO'){
        try {
            const payload = {
                action: 'create',
                unitID,
                customerID,
                dishID,
                dishName,
                dishPrice,
                plates,
                orderPrice,
                orderDate,
                orderTime,
                paymentStatus,
                served
            };
            const response = await fetch(this.orderUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            if(!response.ok) throw new Error('Error adding order');
            return await response.json();
        } catch(error){
            console.error('Error adding order', error);
            return [];
        }
    }
    async getOrders(){
        try {
            const payload = {
                action: 'read',
            };

            const response = await fetch(this.orderUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            if(!response.ok) throw new Error('Error getting orders');
            return await response.json();
        } catch(error){
            console.error('Error getting orders', error);
            return [];
        }
    }
    async updateOrder(orderID, unitID, customerID, dishID, dishName, dishPrice, plates, orderPrice, orderDate, orderTime, paymentStatus = 'Not Paid', served = 'NO'){
        try {
            const payload = {
                action: 'update',
                orderID,
                unitID,
                customerID,
                dishID,
                dishName,
                dishPrice,
                plates,
                orderPrice,
                orderTime,
                paymentStatus,
                served
            };
            const response = await fetch(this.orderUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            if(!response.ok) throw new Error('Error updating orders');
            return await response.json();
        } catch(error){
            console.error('Error updating orders', error);
            return [];
        }
    }
    async deleteOrder(orderID){
        try {
            const payload = {
                action: 'delete',
                orderID
            };
            const response = await fetch(this.orderUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            if(!response.ok) throw new Error('Error deleting order');
            return await response.json();
        } catch(error){
            console.error('Error deleting order', error);
            return [];
        }
    }
}