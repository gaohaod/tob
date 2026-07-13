print("[THUMBNAIL] Render Gear")
local ThumbnailGenerator = game:GetService("ThumbnailGenerator")

pcall(function() game:GetService("ContentProvider"):SetBaseUrl("http://www.noname.xyz/") end)
game:GetService("ScriptContext").ScriptsDisabled = true

for _, object in pairs(game:GetObjects("http://www.noname.xyz/asset/?id={{ hatId }}")) do
	object.Parent = workspace
end

return ThumbnailGenerator:Click("PNG", 220, 220, --[[hideSky = ]] true, --[[crop =]] true)