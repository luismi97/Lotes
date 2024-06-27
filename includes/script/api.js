axios.get('https://api.hubapi.com/crm/v3/objects/contacts',
  {
    headers: {
      'Authorization': `Bearer ${'pat-na1-33ded415-1bc5-41b4-9a8f-48f4f22db657'}`,
      'Content-Type': 'application/json'
    }
  },
  (err, data) => {
    // Handle the API response
    console.log(data);
  }
);