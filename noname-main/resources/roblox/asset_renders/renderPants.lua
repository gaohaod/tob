print("[THUMBNAIL] Render Pants")
local t = game:GetService("ThumbnailGenerator")
local player = game.Players:CreateLocalPlayer(0)
game:GetService("ContentProvider"):SetBaseUrl("http://noname.xyz/")
game:GetService("ScriptContext").ScriptsDisabled = true

player:LoadCharacter(false)

local Camera = Instance.new("Camera", player.Character)

Camera.FieldOfView = 120

player.Character["Right Arm"].BrickColor = BrickColor.new(1)
player.Character["Left Arm"].BrickColor = BrickColor.new(1)
player.Character["Head"].BrickColor = BrickColor.new(1)
player.Character["Torso"].BrickColor = BrickColor.new(1)
player.Character["Right Leg"].BrickColor = BrickColor.new(1)
player.Character["Left Leg"].BrickColor = BrickColor.new(1)

local penis = {{ id }}

local Pants = Instance.new("Pants")
Pants.PantsTemplate = "http://noname.xyz/asset/?id=" .. {{ id }} - 1
Pants.Name = "Pants"
Pants.Parent = player.Character

return game:GetService("ThumbnailGenerator"):Click("PNG", 220, 220, true)