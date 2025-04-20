export default class Dish{
    constructor(){
        this.dishUrl = "/Scriptz/Backend/dishes.php"
    }
    async addDish(unitID, dishName, dishDescription, dishPrice, available = "YES"){
        try {
            const payload = {
                action: 'create',
                unitID,
                dishName, 
                dishDescription,
                dishPrice,
                available
            };
            const response = await fetch(this.dishUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application'
                },
                body: JSON.stringify(payload)
            });
            if(!response.ok) throw new Error('Error adding new dish');
            return await response.json();
        } catch(error){
            console.error('Error adding new dish', error);
            return [];
        }
    }
    async getDishes(unitID){
        try {
            const payload = {
                action: 'read'
            };
            const response = await fetch(this.dishUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            
            if(!response.ok) throw new Error('Error getting dishes');
            return await response.json();
        }catch(error){
            console.error('Error getting dishes', error);
            return [];
        }
    }
    async updateDish(dishID, unitID, dishName, dishDescription){
        try {
            const payload = {
                action: 'update',
                dishID,
                unitID, 
                dishName,
                dishDescription
            };
            const response = await fetch(this.dishUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            if(!response.ok) throw new Error('Error updating dish');
            return await response.json();
        }catch(error){
            console.error('Error updating dish', error);
            return [];
        }
    }
    async deleteDish(dishID){
        try {
            const payload = {
                action: 'delete',
                dishID
            };
            const response = await fetch(this.dishUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            if(!response.ok) throw new Error('Error deleting dish');
            return await response.json();
        } catch(error){
            console.error('Error deleting dish', error);
            return [];
        }
    }
}