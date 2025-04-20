export default class Table{
    constructor(unitID){
        this.unitID = unitID;
        this.getTableUrl = "/Scriptz/Backend/tables.php";
    }
    async addTable(unitID, tableName, tableStatus = "Vacant"){
        try {
            const payload = {
                action: 'create',
                unitID,
                tableName,
                tableStatus
            };

            const response = await fetch(this.getTableUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }, 
                body: JSON.stringify(payload)
            });

            if(!response.ok){
                throw new Error('Error adding table');
            }
            return await response.json();
        } catch(error){
            console.error('There was an error in adding the table');
            return [];
        }
    }
    async getTables(unitID){
        try {
            const payload = {
                action: 'read'
            };
            const response = await fetch(this.getTableUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            if(!response.ok){
                throw new Error('There was an error in fetching tables');
            }
            const allTables = await response.json();
            const thisUnitTables = allTables.filter(table => table.UnitID === unitID);
            return thisUnitTables;
        } catch(error) {
            console.error('Error fetching tables', error);
            return [];
        }
    }
    async updateTable(unitID, tableID, tableName, tableStatus){
        try {
            const payload = {
                action: 'update',
                unitID,
                tableID,
                tableName,
                tableStatus
            };
            const response = await fetch(this.getTableUrl, {
                method: 'POST',
                header: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            return await response.json();
        } catch(error){
            console.error('Error updating table');
            return [];
        }
    }
    async deleteTable(tableID){
        const payload = {
            action: 'delete',
            tableID
          };
      
          const response = await fetch(this.endpoint, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
          });
      
          return await response.json(); // success message
        }
}