local result = pcall(function() game:GetService("ScriptContext"):AddStarterScript(274) end)
if not result then
 pcall(function() game:GetService("ScriptContext"):AddCoreScript(274,game:GetService("ScriptContext"),"StarterScript") end)
end
