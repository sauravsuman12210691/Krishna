// Import required packages
const express = require('express');
const cors = require('cors');


// Use CORS
const bodyParser = require('body-parser'); // Import body-parser to handle JSON request bodies
const { GoogleGenerativeAI } = require("@google/generative-ai");

// Initialize the Express app
const app = express();
app.use(cors());
app.use(bodyParser.json()); // Use body-parser middleware

// Create an instance of GoogleGenerativeAI
const genAI = new GoogleGenerativeAI("AIzaSyAaKJVMsFda41CeP51dZWHTYpM8JDDZlqM"); // Ensure this API key is secured

// Define an async function to handle content generation
async function generateStory(query) {
  try {
    const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash" });
    const prompt = `Use the following question statement to generate a response based on the Bhagavad Gita: "${query}". Please return a JSON array of objects, where each object has the following properties: "solution" (a string containing the solution) "reference" (a string containing chapter and sloka number). Do not generate any additional text, only provide the JSON response.`;


    // Generate content from the model
    const result = await model.generateContent(prompt);
    
    const response = await result.response;
          const text = response.text();

    // Access the generated text correctly
   
    try {
      return JSON.parse(text)
    } catch (jsonError) {
      console.error("Error parsing JSON:", jsonError);
      return { error: "Failed to parse generated response into JSON." };
    }
    
    
  } catch (error) {
    console.error("Error generating story:", error);
    return { error: "Failed to generate story." };
  }
}


// Define a route to handle requests
app.post('/story', async (req, res) => {
  const { query } = req.body; // Destructure the query from request body

  if (!query) {
    return res.status(400).send({ error: "Query is required." }); // Return error if query is not provided
  }

  const solution = await generateStory(query);
  
  // Send the response back to the client
  res.send(solution);
});

// Start the server and listen on port 3000
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});
